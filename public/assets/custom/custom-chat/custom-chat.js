$(document).ready(function () {
    $(document).on('click', '.show-chat-files', function () {
        let chatId = $(this).attr('data-id');
        $('.chat-files').css({
            display: 'block',
        });
        $('.chat-files').html(`
            <div style="display: flex; justify-content: center; align-items: center;">
                <div class="spinner-border"></div>
            </div>
        `);
        $.post('/chat-files', {
            chatId: chatId,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function (res) {
            $('.chat-files').html("");
            $('.chat-files').css({
                display: 'flex',
                flexWrap: 'wrap',
                gap: '10px'
            });

            if (res.length === 0) {
                $('.chat-files').css({
                    display: 'block',
                });
                $('.chat-files').html(`
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <i>No Files</i>
                    </div>
                `);
            }

            res.forEach(file => {
                const fileExtension = file.original_name.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension);
                const filePath = '/storage/' + file.path;

                if (isImage) {
                    $('.chat-files').append(`
                        <div style="width: 100px;">
                            <a href="${filePath}" target="_blank">
                                <img src="${filePath}" alt="${file.original_name}" style="width: 100%; height: auto;" class="img-thumbnail">
                            </a>
                        </div>
                    `);
                } else {
                    $('.chat-files').append(`
                        <div style="width: 100px; text-align: center;">
                            <i class="mb-1 fas fa-file fa-2x"></i><br>
                            <a href="${filePath}" target="_blank" style="font-size: 12px;">${file.original_name}</a>
                        </div>
                    `);
                }
            });
        }).fail(err => {
            console.log(err)
        });
    });


    $('#toggleButton').click(function () {
        $('#friendsSidebar').fadeToggle(200);
        $('#overlay').fadeToggle(200);
    });

    // chat seen couter

    $('#chatCountBadge').text(0);

    const chatSeenCounterPolling = new PollingManager({
        url: `/chat-history`, // API to fetch data
        delay: 5000, // Poll every 5 seconds
        failRetryCount: 3, // Retry on failure
        onSuccess: (chats) => {

            let chatSeenCounter = 0;

            chats.forEach(chat => {
                chatSeenCounter += chat.seen ? 0 : 1;
            });

            chatSeenCounter == 0 ? $('#chatCountBadge').text(0) : $('#chatCountBadge').text(chatSeenCounter)
        },
        onError: (error) => {
            console.error("Error fetching data:", error);
        }
    });

    // Start polling
    chatSeenCounterPolling.start();

    // end chat seen counter

    $('#overlay').click(function () {
        $('#friendsSidebar').fadeOut(200);
        $('#overlay').fadeOut(200);
    });

    $('.friend').click(function () {
        const name = $(this).data('name');
        const friend_id = $(this).data('id'); // ✅ get friend ID

        // Prevent duplicate chatboxes
        if ($('#chatbox-' + friend_id).length > 0) return;

        let auth_id = null; // This is the current logged-in user's ID

        $.get('/user', function (res) {
            auth_id = res.id;

            // create new chat

            $.get('/initialize-chat/' + auth_id + '/' + friend_id, function (res) {

                const polling = new PollingManager({
                    url: `/fetch-messages/${res}`, // API to fetch data
                    delay: 5000, // Poll every 5 seconds
                    failRetryCount: 3, // Retry on failure
                    onSuccess: (messageResponse) => {

                        const chatId = messageResponse.chatId;
                        const chatboxSelector = `#chatbox-${chatId}`;

                        // Build messages HTML
                        let messageHtml = '';
                        messageResponse.messages.forEach(msg => {
                            const isMine = msg.users_id === auth_id;

                            const timestamp = new Date(msg.created_at).toLocaleString('en-US', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true,
                            });

                            function formatMessage(text) {
                                // Convert URLs to clickable links
                                const urlPattern = /(\bhttps?:\/\/[^\s<>"]+[^\s<>"'.])/gi;
                                let linkedText = text.replace(urlPattern, (url) => {
                                    return `<a href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>`;
                                });

                                // Convert newlines (\n) into <br> tags
                                return linkedText.replace(/\n/g, '<br>');
                            }

                            // Handle attachments (images or files)
                            let attachmentsHtml = '';
                            if (msg.files && msg.files.length > 0) {
                                msg.files.forEach(file => {
                                    const ext = file.path.split('.').pop().toLowerCase();
                                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
                                    const fileUrl = `/storage/${file.path}`;

                                    if (isImage) {
                                        attachmentsHtml += `
                                            <div class="chat-attachment">
                                                <a href="${fileUrl}">
                                                    <img src="${fileUrl}" alt="${file.original_name}" style="max-width: 100%; margin-top: 5px; border-radius: 8px;">
                                                </a>
                                            </div>`;
                                    } else {
                                        attachmentsHtml += `
                                            <div class="chat-attachment">
                                                <a href="${fileUrl}" target="_blank">
                                                    ${file.original_name}
                                                </a>
                                            </div>`;
                                    }
                                });
                            }

                            // Append the message with optional attachments
                            messageHtml += `
                                <div class="chat-msg ${isMine ? 'mine' : 'other'}">
                                    <div class="msg-container">
                                        <div class="bubble">
                                            ${formatMessage(msg.message)}
                                            ${attachmentsHtml}
                                        </div>

                                        <div class="timestamp">
                                            ${timestamp}
                                            ${(isMine && msg.message !== 'Unsent a message')
                                                ? `<i class="fa fa-trash unsent-chat text-danger" data-id="${msg.id}"></i>`
                                                : ''}
                                        </div>

                                    </div>
                                </div>
                            `;
                        });

                        // If chatbox already exists, update messages only
                        if ($(chatboxSelector).length > 0) {
                            $(`${chatboxSelector} .chatbox-body`).html(messageHtml);
                        } else {
                            // Otherwise, create a new chatbox
                            const chatbox = `
                                <div class="chatbox" id="chatbox-${chatId}">
                                    <div class="chatbox-header">
                                        <span>${messageResponse.chat.name}</span>
                                        <button data-bs-toggle="modal" data-bs-target="#chatFiles" data-id="${chatId}" class="show-chat-files" title="Show Files"
                                                style="background:none;border:none;color:white;font-size:16px;"><i class="fa fa-file"></i></button>
                                        <button data-id="${chatId}" class="delete-chat" title="Delete Conversation"
                                                style="background:none;border:none;color:white;font-size:16px;"><i class="fa fa-trash"></i></button>
                                        <button class="close-chat" title="Close"
                                                style="background:none;border:none;color:white;font-size:16px;"><i class="fa fa-times"></i></button>
                                    </div>

                                    <div class="chatbox-body">
                                        ${messageHtml}
                                    </div>

                                    <div class="preview-box" id="file-preview-${chatId}"></div>

                                    <div class="chatbox-footer">
                                        <label class="file-icon">
                                            <i class="fas fa-paperclip"></i>
                                            <input type="file" id="file-input-${chatId}" multiple style="display: none;">
                                        </label>

                                        <textarea class="chat-input" placeholder="Type a message…" rows="1"></textarea>

                                        <button type="submit" class="send-btn" title="Send" id="send-btn">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                            `;

                            $('#chatboxes-container').append(chatbox);

                            // File Preview with Remove Option

                            let selectedFiles = [];

                            // Handle file selection
                            $('#file-input-' + chatId).on('change', function (e) {
                                selectedFiles = Array.from(e.target.files);
                                renderFilePreviews();
                            });

                            // Render file previews
                            function renderFilePreviews() {
                                $('#file-preview-' + chatId).empty();

                                selectedFiles.forEach((file, index) => {
                                    const isImage = file.type.startsWith('image/');
                                    const fileItem = $('<div>')
                                        .addClass('file-item')
                                        .attr('data-index', index)
                                        .css({
                                            display: 'inline-block',
                                            position: 'relative',
                                            marginRight: '10px',
                                            marginBottom: '10px'
                                        });

                                    if (isImage) {
                                        const reader = new FileReader();
                                        reader.onload = function (e) {
                                            fileItem.append(`<img src="${e.target.result}" alt="${file.name}" style="max-width:80px;">`);
                                            fileItem.append(`<span class="remove-file" style="position:absolute;top:0;right:0;background:#ff4444;color:#fff;border-radius:50%;width:18px;height:18px;line-height:18px;text-align:center;font-size:12px;cursor:pointer;">&times;</span>`);
                                            $('#file-preview-' + chatId).append(fileItem);
                                        };
                                        reader.readAsDataURL(file);
                                    } else {
                                        fileItem.append(`<div>${file.name}</div>`);
                                        fileItem.append(`<span class="remove-file" style="margin-left:10px;color:red;cursor:pointer;">[Remove]</span>`);
                                        $('#file-preview-' + chatId).append(fileItem);
                                    }
                                });
                            }

                            // Handle file removal
                            $(document).on('click', '.remove-file', function () {
                                const index = $(this).closest('.file-item').data('index');
                                selectedFiles.splice(index, 1);
                                renderFilePreviews();
                            });

                            // Send Button

                            $(`#chatbox-${chatId} .send-btn`).click(function () {
                                let chatInput = $(`#chatbox-${chatId} .chat-input`).val();
                                let usersId = auth_id;
                                let hasAttachments = selectedFiles.length > 0 ? 1 : 0;

                                $(`#chatbox-${chatId} .chatbox-body`).append(`
                                    <div class="chat-msg mine">
                                        <div class="msg-container" style="width: 100%">
                                            <div class="bubble d-flex align-items-center">
                                                <div class="spinner-border me-2" style="width: 10px; height: 10px;"></div>
                                                <span>Sending...</span>
                                            </div>
                                            <div class="timestamp">just now</div>
                                        </div>
                                        <div id="latest-message"></div>
                                    </div>
                                `);

                                setTimeout(() => {
                                    document.querySelector(`#chatbox-${chatId} #latest-message`)?.scrollIntoView({ behavior: 'smooth' });
                                }, 50);

                                // First: send message
                                $.post('/send-chat', {
                                    'hasAttachments': hasAttachments,
                                    'message': chatInput,
                                    'chats_id': chatId,
                                    'senders_id': usersId,
                                    "_token": $('meta[name="csrf-token"]').attr('content')
                                }, function (res) {
                                    const messageId = res.message_id;

                                    // If no files, we're done
                                    if (!hasAttachments) {
                                        clearChatInput(chatId);
                                        return;
                                    }

                                    // Else, upload files linked to that message
                                    const formData = new FormData();
                                    formData.append('chats_id', chatId);
                                    formData.append('messages_id', messageId);
                                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                                    selectedFiles.forEach(file => {
                                        formData.append('files[]', file);
                                    });

                                    $.ajax({
                                        url: '/upload-files',
                                        method: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function (uploadRes) {
                                            clearChatInput(chatId);
                                        },
                                        error: function (err) {
                                            console.error('File upload failed:', err);
                                        }
                                    });

                                }).fail(function (err) {
                                    console.error('Message failed:', err);
                                });
                            });

                            function clearChatInput(chatId) {
                                $(`#chatbox-${chatId} .chat-input`).val("");
                                $('#file-preview-' + chatId).empty();
                                selectedFiles = [];
                            }

                            // end of the sent button
                        }
                    },
                    onError: (error) => {
                        console.error("Error fetching data:", error);
                        // Your custom error handling logic
                    }
                });

                // Start polling
                polling.start();

            }).fail(err => {
                console.log(err)
            });

        })
    });

    // Delegate close event
    $(document).on('click', '.close-chat', function () {
        $(this).closest('.chatbox').remove();
    });

    // Delegate close event
    $(document).on('click', '.delete-chat', function () {
        let chatId = $(this).attr('data-id');

        Swal.fire({
            title: "Do you want to delete this chat? It will be gone forever, and the other person might be confused.",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `Don't delete`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Deleting...',
                    text: '',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                });

                $.post('/delete-chat', {
                    chatId: chatId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function (res) {
                    $("#chatbox-" + chatId).remove();
                    Swal.fire("Deleted!", "", "success");
                }).fail(err => {
                    $("#chatbox-" + chatId).remove();
                    Swal.fire("Something went wrong!", "", "danger");
                })

            } else if (result.isDenied) {
                Swal.fire("Deletion cancelled", "", "info");
            }
        });
    });

    // unsent chat

    $(document).on('click', '.unsent-chat', function () {
        let messageId = $(this).attr('data-id');

        Swal.fire({
            title: "Do you want to unsent this message?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `Don't delete`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Unsending...',
                    text: '',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                });

                $.post('/unsent-message', {
                    messageId: messageId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function (res) {
                    Swal.fire("Unsent!", "", "success");
                }).fail(err => {
                    $("#chatbox-" + chatId).remove();
                    Swal.fire("Something went wrong!", "", "danger");
                })

            } else if (result.isDenied) {
                Swal.fire("Cancelled", "", "info");
            }
        });
    });
});
