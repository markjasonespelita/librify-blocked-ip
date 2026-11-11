(function() {
    const style = document.createElement('style');
    style.textContent = `
            .chat-toggle-btn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                background: #1D001D;
                color: white;
                border: none;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                font-size: 24px;
                cursor: pointer;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            }
            .chatbox-wrapper {
                position: fixed;
                bottom: 90px;
                right: 20px;
                width: 100%;
                max-width: 30%;
                height: 60%;
                background: white;
                border-radius: 15px;
                box-shadow: 0 0 20px rgba(0,0,0,0.2);
                display: none;
                flex-direction: column;
                z-index: 1000;
                overflow: hidden;
            }
            .chatbox-header {
                color: white;
                padding: 10px;
                font-weight: bold;
                text-align: center;
            }
            .chatbox-messages {
                flex: 1;
                overflow-y: auto;
                padding: 10px;
                display: flex;
                flex-direction: column;
            }
            .chatbox-message {
                margin: 5px 0;
                padding: 8px 12px;
                border-radius: 10px;
                max-width: 80%;
                white-space: pre-wrap;
            }
            .chatbox-message.user {
                background: #0C8EFD;
                align-self: flex-end;
                color: #fff;
            }
            .chatbox-message.bot {
                background: #e2e3e5;
                align-self: flex-start;
            }
            .chatbox-footer {
                display: flex;
                padding: 10px;
                gap: 5px;
                border-top: 1px solid #eee;
                background: white;
            }
            .chatbox-footer input {
                flex: 1;
                padding: 6px 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            .chatbox-footer button {
                background: #0C8EFD;
                color: white;
                border: none;
                padding: 6px 12px;
                border-radius: 5px;
                cursor: pointer;
            }
            .chatbox-typing {
                font-style: italic;
                color: gray;
                padding: 4px 10px;
            }
            .dropdown {
                padding: 10px;
            }
            .dropdown-menu.show {
                display: block;
                position: static;
                float: none;
            }
            .dropdown-item {
                cursor: pointer;
            }

            @media (max-width: 480px) {
                .chatbox-wrapper {
                max-width: 95vw;
                height: 80vh;

                bottom: 80px;
                right: 10px;
                border-radius: 10px;
                }
                .chat-toggle-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
                bottom: 20px;
                right: 15px;
                }
            }
            `;
    document.head.appendChild(style);

    const chatButton = document.createElement('button');
    chatButton.className = 'chat-toggle-btn';
    chatButton.innerHTML = '&#128172;';
    document.body.appendChild(chatButton);

    const chatContainer = document.createElement('div');
    chatContainer.className = 'chatbox-wrapper';

    const faqData = [{
            question: "How to add an item?",
            response: "How to add an item \n\n1. Navigate to the Items page \n2. Click the 'Add Item' button on the top-right corner of the page and fill out the required fields. \n\nRemember: Determine first whether the item has a serial number or not before proceeding."
        },
        {
            question: "How to edit/update an item?",
            response: "How to edit/update an item \n\n1. Click the edit icon. \nNote: Items with serial numbers and those without are handled differently. If you want to decrease the quantity of an item with a serial number, click the specific serial number you want to remove—its quantity will decrease. For items without a serial number, simply update their quantity."
        },
        {
            question: "How to delete an item?",
            response: "To delete an item, use the delete icon next to it."
        },
        {
            question: "Where to find settings?",
            response: "Settings can be found under the top-right user menu."
        }
    ];

    // Generate dropdown HTML from JSON
    const dropdownHTML = faqData.map(item =>
        `<li><a class="dropdown-item" href="#" data-question="${item.question}" data-response="${item.response}">${item.question}</a></li>`
    ).join('');

    // Insert HTML into chat container
    chatContainer.innerHTML = `
            <div ng-app="chatApp" ng-controller="ChatController" style="display: flex; flex-direction: column; height: 100%;">
                <div class="chatbox-header">Chat with GPT</div>
                <div class="chatbox-messages" id="chatboxMessages">
                <div ng-if="!beginConversation" class="my-3 text-center no-chat-placeholder text-muted">
                    <i class="mb-2 fas fa-robot fa-2x d-block"></i>
                    <small>
                        This is an automated chatbot together with the GPT model. <br>
                        For system-related questions, click "Ask question about the system." <br>
                        For a general AI conversation, simply type your message below. <br>
                        Note: GPT model does not have knowledge about our specific system.
                    </small>
                </div>
                <div ng-repeat="msg in messages" class="chatbox-message" ng-class="msg.sender">[[ msg.text ]]</div>
                <div class="chatbox-typing" ng-show="isTyping">Bot is typing...</div>
                </div>
                <div class="dropdown">
                <input class="mb-2 form-control" id="dropdownSearch" type="text" placeholder="Ask question about the system...">
                <ul class="dropdown-menu show w-100" id="customDropdown" style="display:none;">
                    ${dropdownHTML}
                </ul>
                </div>
                <form ng-submit="sendMessage()" class="chatbox-footer">
                <input type="text" ng-model="userInput" placeholder="Send a message to GPT..." required>
                <button type="submit">Send</button>
                </form>
            </div>
            `;



    document.body.appendChild(chatContainer);

    chatButton.addEventListener('click', () => {
        chatContainer.style.display = chatContainer.style.display === 'flex' ? 'none' : 'flex';
        const msgBox = document.getElementById('chatboxMessages');
        setTimeout(() => {
            msgBox.scrollTop = msgBox.scrollHeight;
        }, 100);
    });

    const app = angular.module('chatApp', []);

    app.config(['$interpolateProvider', function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]);

    app.controller('ChatController', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
        $scope.messages = [];
        $scope.userInput = '';
        $scope.isTyping = false;

        $scope.beginConversation = false;

        const scrollToBottom = () => {
            $timeout(() => {
                const container = document.getElementById('chatboxMessages');
                container.scrollTop = container.scrollHeight;
            }, 50);
        };

        $scope.sendMessage = function() {

            $scope.beginConversation = true;

            const input = $scope.userInput.trim();
            if (!input) return;

            $scope.messages.push({
                text: input,
                sender: 'user'
            });
            $scope.userInput = '';
            $scope.isTyping = true;
            scrollToBottom();

            $http.get('https://text.pollinations.ai/' + encodeURIComponent(input), {
                headers: {
                    'Content-Type': 'text/plain'
                }
            }).then(function(response) {
                const fullText = response.data;
                let typedText = '';
                let i = 0;

                function typeChar() {
                    if (i < fullText.length) {
                        typedText += fullText.charAt(i);
                        $timeout(() => {
                            if ($scope.messages[$scope.messages.length - 1].sender !== 'bot') {
                                $scope.messages.push({
                                    text: '',
                                    sender: 'bot'
                                });
                            }
                            $scope.messages[$scope.messages.length - 1].text = typedText;
                            scrollToBottom();
                            i++;
                            typeChar();
                        }, 20);
                    } else {
                        $scope.isTyping = false;
                    }
                }

                typeChar();
            }).catch(function(error) {
                $scope.isTyping = false;
                $scope.messages.push({
                    text: 'Error: Something went wrong, please check your internet connection and try again.',
                    sender: 'bot'
                });
                scrollToBottom();
            });
        };
    }]);

    // angular.bootstrap(chatContainer, ['chatApp']);

    // Dropdown search & select logic
    const dropdownSearch = document.getElementById('dropdownSearch');
    const dropdownItems = document.querySelectorAll('#customDropdown .dropdown-item');

    dropdownSearch.addEventListener('input', function() {
        document.getElementById('customDropdown').style.display = 'block';
        const filter = this.value.toLowerCase();
        dropdownItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(filter) ? 'block' : 'none';
        });
    });

    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {

            const scopeElBeginConversation = document.querySelector('[ng-controller="ChatController"]');
            if (scopeElBeginConversation) {
                const scope = angular.element(scopeElBeginConversation).scope();
                scope.$apply(() => {
                    scope.beginConversation = true;
                });
            }

            e.preventDefault();
            document.getElementById('customDropdown').style.display = 'none';

            const question = this.getAttribute('data-question');
            const response = this.getAttribute('data-response');

            const scopeEl = document.querySelector('[ng-controller="ChatController"]');
            if (scopeEl) {
                const scope = angular.element(scopeEl).scope();
                scope.$apply(() => {
                    scope.messages = scope.messages || [];
                    scope.messages.push({
                        text: question,
                        sender: 'user'
                    });
                    scope.isTyping = true;
                });

                let typedText = '';
                let i = 0;

                function typeChar() {
                    if (i < response.length) {
                        typedText += response.charAt(i);
                        setTimeout(() => {
                            scope.$apply(() => {
                                const messages = scope.messages;
                                if (!messages.length || messages[messages.length - 1].sender !== 'bot') {
                                    messages.push({
                                        text: '',
                                        sender: 'bot'
                                    });
                                }
                                messages[messages.length - 1].text = typedText;
                                scope.isTyping = true;

                                const container = document.getElementById('chatboxMessages');
                                if (container) container.scrollTop = container.scrollHeight;
                                i++;
                                typeChar();
                            });
                        }, 20);
                    } else {
                        // Delay slightly to ensure DOM updates settle before marking as done
                        setTimeout(() => {
                            scope.$apply(() => {
                                scope.isTyping = false;
                            });
                        }, 50);
                    }
                }

                typeChar();

            }

            dropdownSearch.value = '';
        });
    });

})();
