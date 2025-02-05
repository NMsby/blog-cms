class NotificationManager {
    constructor() {
        this.unreadCount = 0;
        this.notifications = [];
        this.notificationSound = new Audio('/notification.mp3');
        this.initialize();
    }

    async initialize() {
        if (window.Echo && window.userId) {
            this.setupNotificationListeners();
        }

        await this.updateUnreadCount();
        this.setupPolling();
    }

    setupNotificationListeners() {
        window.Echo.private(`notifications.${window.userId}`)
            .listen('.notification.received', (notification) => {
                this.handleNewNotification(notification);
            });
    }

    async handleNewNotification(notification) {
        this.notifications.unshift(notification);
        this.unreadCount++;
        await this.updateUI();
        this.showToast(notification);
        this.playNotificationSound();
    }

    async updateUI() {
        const badge = document.getElementById('notification-badge');
        if (badge) {
            badge.textContent = this.unreadCount;
            badge.classList.toggle('hidden', this.unreadCount === 0);
        }

        await this.updateNotificationList();
    }

    showToast(notification) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-white shadow-lg rounded-lg p-4 z-50 transform transition-all duration-300';
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900">${notification.title || 'New Notification'}</p>
                    <p class="mt-1 text-sm text-gray-500">${notification.message}</p>
                </div>
                <button class="ml-4 text-gray-400 hover:text-gray-500" onclick="this.parentElement.parentElement.remove()">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.transform = 'translateY(10px)';
            requestAnimationFrame(() => {
                toast.style.transform = 'translateY(0)';
            });
        });

        setTimeout(() => {
            toast.style.transform = 'translateY(-10px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    async updateNotificationList() {
        const container = document.querySelector('.notifications-container');
        if (!container) return;

        try {
            const response = await fetch('/notifications/partial');
            if (response.ok) {
                container.innerHTML = await response.text();
            }
        } catch (error) {
            console.error('Error updating notifications list:', error);
        }
    }

    async updateUnreadCount() {
        try {
            const response = await fetch('/notifications/count');
            if (response.ok) {
                const data = await response.json();
                this.unreadCount = data.count;
                await this.updateUI();
            }
        } catch (error) {
            console.error('Error updating notification count:', error);
        }
    }

    setupPolling() {
        setInterval(async () => {
            await this.updateUnreadCount();
        }, 60000);
    }

    playNotificationSound() {
        const userPreferences = this.getUserPreferences();
        if (userPreferences.sound_enabled) {
            this.notificationSound.play().catch(error => {
                console.error('Error playing notification sound:', error);
            });
        }
    }

    getUserPreferences() {
        return {
            sound_enabled: localStorage.getItem('notification_sound_enabled') !== 'false',
        };
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                await this.updateUnreadCount();
                await this.updateNotificationList();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.notificationManager = new NotificationManager();
});
