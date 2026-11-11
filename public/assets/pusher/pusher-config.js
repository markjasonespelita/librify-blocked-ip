Pusher.logToConsole = false;

const pusher = new Pusher('a72b63426e63f0bb9d29', {
    cluster: 'ap2',
    forceTLS: true,
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
});
