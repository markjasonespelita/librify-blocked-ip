import { initializeApp } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-app.js";
import { getMessaging, getToken, onMessage }
    from "https://www.gstatic.com/firebasejs/12.6.0/firebase-messaging.js";

// Firebase config
const firebaseConfig = {

};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Register service worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/assets/firebase/firebase-messaging-sw.js')
        .then(/*() => console.log('Service Worker registered')*/)
        .catch(err => console.error('SW registration failed', err));
}

// Get permission and token
async function initFCM() {
    const permission = await Notification.requestPermission();

    if (permission === 'granted') {
        const vapidKey = "BJqTEghZqtxl8YkahYe7LXoObS84XbbUchKAnaSYKVigojQmkgWb2qMWnaIT-5huNr2MrzXFUSq9Bva-eoDEbEU"; // Web Push certificate

        const token = await getToken(messaging, { vapidKey });

        // console.log("FCM TOKEN:", token);

        // Save to Laravel
        await fetch("/save-fcm-token", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ token }),
        })
        .then(async response => {
            if (!response.ok) {
                // Extract error message from Laravel response
                const errorText = await response.text();
                console.error("Server Error:", errorText);
                throw new Error("Server returned an error");
            }

            return response.json();
        })
        .then(data => {
            // console.log("Token saved:", data);
        })
        .catch(error => {
            console.error("Fetch Error:", error);
        });

    }
}

initFCM();

// Foreground notifications
onMessage(messaging, (payload) => {
    new Notification(payload.notification.title, {
        body: payload.notification.body,
        icon: payload.notification.icon,
        data: {
            url: payload.data?.taskUrl
        }
    });
});
