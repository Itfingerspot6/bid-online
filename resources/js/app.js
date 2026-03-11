import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Listen for notifications
const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');

if (userId && window.Echo) {
    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            console.log('Notification received:', notification);
            if (window.showToast) {
                window.showToast(notification.message || 'Anda menerima notifikasi baru');
            }

            // Optional: Dispatch event to update notification bell count if needed
            window.dispatchEvent(new CustomEvent('notification-received', { detail: notification }));
        });
}
