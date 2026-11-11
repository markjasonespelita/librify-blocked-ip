
<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
    <head>
        <meta charset='utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name='csrf-token' content='{{ csrf_token() }}'>
        <meta name='author' content='Mark Jason Penote Espelita'>
        <meta name='keywords' content='keyword1, keyword2'>
        <meta name='description' content='Dolorem natus ab illum beatae error voluptatem incidunt quis. Cupiditate ullam doloremque delectus culpa. Autem harum dolorem praesentium dolorum necessitatibus iure quo. Et ea aut voluptatem expedita.'>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href='{{ url('assets/bootstrap/bootstrap.min.css') }}' rel='stylesheet'>
        <!-- FontAwesome for icons -->
        <link href='{{ url('assets/font-awesome/css/all.min.css') }}' rel='stylesheet'>
        <link rel='stylesheet' href='{{ url('assets/custom/style.css') }}'>
        <link rel='icon' href='{{ url('assets/logo.png') }}'>
    </head>
    <body class='font-sans antialiased'>

        <!-- Sidebar for Desktop View -->
        <div class='sidebar' id='mobileSidebar'>
            <div class='logo'>
                <img src='{{ url('assets/logo.png') }}' alt='' width='100%'> <br>
                    <x-version-component />
            </div>

            @php
                $navLinks = [
                    [
                        'url' => '_database',
                        'icon' => 'fas fa-times',
                        'label' => 'Database',
                        'permission' => 'can view database',
                        'active' => request()->is('_database'),
                    ],
                    [
                        'url' => '_postman',
                        'icon' => 'fas fa-times',
                        'label' => 'Postman',
                        'permission' => 'can view postman',
                        'active' => request()->is('_postman'),
                    ],
                    [
                        'url' => 'dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'label' => 'Dashboard',
                        'permission' => 'can view dashboard',
                        'active' => request()->is('dashboard'),
                    ],
                    [
                        'url' => 'activity-logs',
                        'icon' => 'fas fa-bars',
                        'label' => 'Logs',
                        'permission' => 'can view logs',
                        'active' => request()->is(
                            'activity-logs',
                            'create-logs',
                            'show-logs/*',
                            'edit-logs/*',
                            'delete-logs/*',
                            'logs-search*'
                        ),
                    ],
                    [
                        'url' => 'users',
                        'icon' => 'fas fa-users',
                        'label' => 'Users',
                        'permission' => 'can view users',
                        'active' => request()->is(
                            'users',
                            'create-users',
                            'trash-users',
                            'show-users/*',
                            'edit-users/*',
                            'delete-users/*',
                            'users-search*'
                        ),
                    ],

                    // another models

                    [
                        'url' => 'statusreports',
                        'icon' => 'fas fa-battery-half',
                        'label' => 'Statusreports',
                        'permission' => 'can view statusreports',
                        'active' => request()->is(
                            'statusreports',
                            'create-statusreports',
                            'trash-statusreports',
                            'show-statusreports/*',
                            'edit-statusreports/*',
                            'delete-statusreports/*',
                            'statusreports-search*'
                        ),
                    ],

                    // another models


                    [
                        'url' => 'sites',
                        'icon' => 'fas fa-location-crosshairs',
                        'label' => 'Sites',
                        'permission' => 'can view sites',
                        'active' => request()->is(
                            'sites',
                            'create-sites',
                            'trash-sites',
                            'show-sites/*',
                            'edit-sites/*',
                            'delete-sites/*',
                            'sites-search*'
                        ),
                    ],

                    // another models


                    [
                        'url' => 'statuses',
                        'icon' => 'fas fa-exclamation-triangle',
                        'label' => 'Statuses',
                        'permission' => 'can view statuses',
                        'active' => request()->is(
                            'statuses',
                            'create-statuses',
                            'trash-statuses',
                            'show-statuses/*',
                            'edit-statuses/*',
                            'delete-statuses/*',
                            'statuses-search*'
                        ),
                    ],

                    // another models


                    [
                        'url' => 'olts',
                        'icon' => 'fas fa-wifi',
                        'label' => 'Olts',
                        'permission' => 'can view olts',
                        'active' => request()->is(
                            'olts',
                            'create-olts',
                            'trash-olts',
                            'show-olts/*',
                            'edit-olts/*',
                            'delete-olts/*',
                            'olts-search*'
                        ),
                    ],

                    // another models


                    [
                        'url' => 'municipalities',
                        'icon' => 'fas fa-map-marker',
                        'label' => 'Municipalities',
                        'permission' => 'can view municipalities',
                        'active' => request()->is(
                            'municipalities',
                            'create-municipalities',
                            'trash-municipalities',
                            'show-municipalities/*',
                            'edit-municipalities/*',
                            'delete-municipalities/*',
                            'municipalities-search*'
                        ),
                    ],

                    // another models


                    [
                        'url' => 'spatie-roles',
                        'icon' => 'fas fa-user-shield',
                        'label' => 'Roles',
                        'permission' => 'can view role',
                        'active' => request()->is(
                            'spatie-roles',
                        ),
                    ],
                    [
                        'url' => 'receive-chat-notification',
                        'icon' => 'fas fa-comment',
                        'label' => 'Messenger Notification',
                        'permission' => 'can view messenger notification',
                        'active' => request()->is(
                            'receive-chat-notification',
                            'create-receive-chat-notification',
                            'trash-receive-chat-notification',
                            'show-receive-chat-notification/*',
                            'edit-receive-chat-notification/*',
                            'delete-receive-chat-notification/*',
                            'receive-chat-notification-search*'
                        ),
                    ],
                ];
        @endphp

        @foreach ($navLinks as $link)
            @can($link['permission'])
                <a href="{{ url($link['url']) }}" class="{{ $link['active'] ? 'active' : '' }}">
                    <i class="{{ $link['icon'] }}"></i> {{ $link['label'] }}
                </a>
            @endcan
        @endforeach

        <a href="/release-notes">
            <i class="fas fa-file-alt"></i> Release Notes
        </a>

        <a href="{{ url('user/profile') }}">
            <i class="fas fa-user"></i> {{ Auth::user()->name }}
        </a>

        </div>

        <!-- Top Navbar -->
        <nav class='navbar navbar-expand-lg navbar-dark'>
            <div class='container-fluid'>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav'
                    aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation' onclick='toggleSidebar()'>
                    <i class='fas fa-bars'></i>
                </button>
            </div>
        </nav>

        <x-main-notification />

        <div class='content'>
            @yield('content')

            <!-- Toggle Button -->
            <button id="toggleButton">
                <i class="fas fa-user"></i>
                <span id="chatCountBadge">0</span>
            </button>

            {{-- <h1 class="sample">hello</h1> --}}

            <!-- Dark background overlay -->
            <div id="overlay" style="display: none;"></div>

            <!-- Styles -->
            <style>
                body {
                    margin: 0;
                    font-family: Arial, sans-serif;
                }

                /* SIDEBAR */
                #friendsSidebar {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    max-width: 30%;
                    height: 100vh;
                    background: #ffffff;
                    border-right: 1px solid #e0e0e0;
                    padding: 1rem;
                    overflow-y: auto;
                    display: none;
                    z-index: 1001;
                    box-shadow: 4px 0 12px rgba(0, 0, 0, 0.05);
                }

                .friend {
                    padding: 12px 16px;
                    margin-bottom: 10px;
                    background: #f8f9fa;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    cursor: pointer;
                    transition: all 0.2s ease-in-out;
                    border: 1px solid transparent;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
                }

                .friend:hover {
                    background: #e9f5ff;
                    border-color: #007bff;
                    transform: translateY(-2px);
                }

                #toggleButton {
                    position: fixed;
                    bottom: 6rem;
                    right: 2rem;
                    width: 56px;
                    height: 56px;
                    background: linear-gradient(135deg, #005f9f, #0084ff);
                    color: white;
                    border: none;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 22px;
                    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
                    transition: background 0.3s, transform 0.2s;
                }

                #toggleButton:hover {
                    transform: scale(1.1);
                    background: linear-gradient(135deg, #0072ce, #009bff);
                }

                #chatCountBadge {
                    position: absolute;
                    top: -4px;
                    right: -4px;
                    background-color: red;
                    color: white;
                    font-size: 20px;
                    font-weight: bold;
                    padding: 2px 5px;
                    border-radius: 50%;
                    line-height: 1;
                }

                /* OVERLAY */
                #overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    height: 100vh;
                    background-color: rgba(0, 0, 0, 0.4);
                    backdrop-filter: blur(3px);
                    z-index: 1000;
                    display: none;
                }

                /* CHATBOX */
                .chatbox {
                    width: 100%;
                    max-width: 400px;
                    height: 50vh;
                    background: white;
                    border: none;
                    border-radius: 20px 20px 0 0;
                    box-shadow: none;
                    display: flex;
                    flex-direction: column;
                }

                .chatbox-header {
                    background: #0C8EFD;
                    border-radius: 20px 20px 0 0;
                    color: white;
                    padding: 12px 15px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-weight: bold;
                }

                .chatbox-body {
                    padding: 10px;
                    flex: 1;
                    overflow-y: auto;
                }

                .chatbox-footer {
                    padding: 8px;
                    border-top: 1px solid #ccc;
                }

                .chatbox-footer textarea {
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    font-size: 16px;
                }

                .mine .msg-container {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end; /* Align timestamp to the right */
                    max-width: 80%;
                }

                /* MESSAGES */
                .chat-msg {
                    display: flex;
                    margin: 10px 0;
                }

                .chat-msg.mine {
                    justify-content: flex-end;
                }

                .chat-msg.other {
                    justify-content: flex-start !important;
                }

                .chat-msg .bubble {
                    display: inline-block;
                    padding: 10px 14px;
                    border-radius: 18px;
                    position: relative;
                    font-size: 14px;
                    color: #000;
                    word-break: break-word;
                    max-width: 80%; /* still useful for long messages */
                    width: fit-content;
                }

                .chat-msg.other .bubble {
                    background-color: #cacaca;
                    align-self: flex-start; /* ensure it's not stretched */
                }

                .chat-msg.other .bubble::after {
                    content: "";
                    position: absolute;
                    top: 10px;
                    left: -6px;
                    width: 0;
                    height: 0;
                    border-top: 8px solid transparent;
                    border-right: 8px solid #cacaca;
                    border-bottom: 8px solid transparent;
                }

                .chat-msg.mine .bubble {
                    background: linear-gradient(135deg, #0084ff, #44bef1);
                    color: white;
                }

                .chat-msg.mine .bubble::after {
                    content: "";
                    position: absolute;
                    top: 10px;
                    right: -6px;
                    width: 0;
                    height: 0;
                    border-top: 8px solid transparent;
                    border-left: 8px solid #44bef1;
                    border-bottom: 8px solid transparent;
                }

                .timestamp {
                    font-size: 11px;
                    margin-top: 5px;
                    color: #000000;
                    text-align: right;
                }

                /* File upload */

                .preview-box {
                    margin-bottom: 10px;
                }

                .preview-box img {
                    max-width: 100px;
                    margin-right: 10px;
                }

                .file-icon {
                    font-size: 18px;
                    cursor: pointer;
                    margin-right: 10px;
                    color: #444;
                }

                /* MEDIA QUERY FOR MOBILE */
                @media (max-width: 768px) {
                    #friendsSidebar {
                        width: 100%;
                        max-width: none;
                        padding: 0.8rem;
                    }

                    .chatbox {
                        width: 100vw;
                        height: 90vh;
                        border-radius: 0;
                    }

                    #chatboxes-container {
                        right: 0 !important;
                    }

                    #toggleButton {
                        width: 50px;
                        height: 50px;
                        bottom: 6rem;
                        right: 1rem;
                        font-size: 20px;
                    }

                    .chatbox-footer input {
                        padding: 10px;
                        font-size: 14px;
                    }

                    .chat-msg .bubble {
                        font-size: 13px;
                        max-width: 85%;
                    }
                }
            </style>

            <!-- Make sure this comes BEFORE the script -->
            <x-side-chat :chats="App\Models\Chats::all()" />

            <!-- Chat Files Modal HTML -->
            <div class="modal fade" id="chatFiles" tabindex="-1" aria-labelledby="chatFilesLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="chatFilesLabel">Files</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="chat-files" style=""></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Chat Files Modal HTML -->

            <!-- Chatbox Container -->
            <div id="chatboxes-container" style="position: fixed; bottom: 0; right: 90px; display: flex; gap: 10px; z-index: 1200;"></div>

        </div>

        <x-seasons-component />

        {{-- apex charts --}}

        <script src="{{ url('assets/apexcharts/apexcharts.min.js') }}"></script>

        <!-- Bootstrap JS and dependencies -->
        <script src='{{ url('assets/bootstrap/bootstrap.bundle.min.js') }}'></script>

        <!-- jQuery CDN -->
        <script src="{{ url('assets/jquery/jquery.min.js') }}"></script>
        <script src="{{ url('assets/sweetalert/sweetalert.min.js') }}"></script>

        <!-- Script should be AFTER x-side-chat -->
        <script src="{{ url('assets/custom/custom-chat/custom-chat.js') }}"></script>

        <!-- Custom JavaScript -->
        <script src="{{ url('assets/custom/script.js') }}"></script>
        <script src="{{ url('assets/angular/angular.min.js') }}"></script>
        <script src="{{ url('assets/custom/bot/chatbot.js') }}"></script>

        {{-- firebase cloud messaging --}}

        <script type="module" src="{{ url('assets/firebase/firebase-config.js') }}"></script>

        <script src="{{ url('assets/pusher/pusher.min.js') }}"></script>
        <script src="{{ url('assets/pusher/pusher-config.js') }}"></script>

        {{-- localstorage --}}
        <script src="{{ url('assets/custom/localstorage/localstorage.js') }}"></script>

        <script>
            $(document).ready(function () {
                // console.log('👤 Authenticated User ID:', res.id);

                // Pusher.logToConsole = true;

                // ✅ Subscribe to the PRIVATE channel
                const channel = pusher.subscribe('private-hello-message.' +  '{{ Auth::user()->id }}');

                // ✅ Event name matches your PHP (hello-message-event)
                channel.bind('hello-message-event', function (data) {
                    console.log('✅ Received from Laravel:', data);
                    // $('.sample').html(data.message)
                });
            });
        </script>

        <style>
            #version-popup {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                animation: jumpIn 0.8s ease, bounce 2s infinite 1s;
            }

            .vp-box {
                width: 280px;
                background: #ffffff;
                color: #000000;
                padding: 14px 16px;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0,0,0,.3);
            }

            .vp-box h3 {
                font-size: 16px;
                margin: 0 0 6px;
            }

            .vp-box p {
                font-size: 14px;
                margin: 0 0 8px;
            }

            .vp-box a {
                color: #7b7b7b;
                font-size: 13px;
                text-decoration: underline;
            }

            .vp-close {
                position: absolute;
                top: 6px;
                right: 10px;
                background: none;
                border: none;
                color: #ff0000;
                font-size: 18px;
                cursor: pointer;
            }

            /* Jump in animation */
            @keyframes jumpIn {
                0% { transform: translateY(-30px); opacity: 0; }
                60% { transform: translateY(6px); }
                100% { transform: translateY(0); opacity: 1; }
            }

            /* Continuous bounce */
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-6px); }
            }
        </style>

        <script>
            (function () {
                const GITHUB_OWNER = "{{ config('app.github_owner') }}";
                const GITHUB_REPO = "{{ config('app.github_repo') }}";
                const GITHUB_TOKEN = "{{ config('app.github_token') }}";

                const STORAGE_KEY = 'last_seen_app_version';

                function createPopup(version, notesUrl) {
                    const popup = document.createElement('div');
                    popup.id = 'version-popup';
                    popup.innerHTML = `
                        <div class="vp-box">
                            <button class="vp-close">&times;</button>
                            <h3>🚀<strong>${version}</strong> Has Been Released</h3>
                            <a href="${notesUrl}" target="_blank">View release notes</a>
                        </div>
                    `;

                    document.body.appendChild(popup);

                    popup.querySelector('.vp-close').addEventListener('click', () => {
                        localStorage.setItem(STORAGE_KEY, version);
                        popup.remove();
                    });
                }

                async function checkVersion() {
                    try {
                        const response = await fetch(
                            `https://api.github.com/repos/${GITHUB_OWNER}/${GITHUB_REPO}/releases/latest`,
                            {
                                headers: {
                                    Authorization: `token ${GITHUB_TOKEN}`,
                                    Accept: 'application/vnd.github+json'
                                }
                            }
                        );

                        const data = await response.json();
                        const latestVersion = data.tag_name;
                        const lastSeen = localStorage.getItem(STORAGE_KEY);

                        if (latestVersion && latestVersion !== lastSeen) {
                            createPopup(latestVersion, '/release-notes');
                        }

                    } catch (error) {
                        console.error('Version check failed:', error);
                    }
                }

                document.addEventListener('DOMContentLoaded', checkVersion);
            })();

        </script>
    </body>
</html>
