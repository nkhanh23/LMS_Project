/**
 * StackLearn Notification Hub
 * Alpine.js components cho Notification Dropdown và Toast popup.
 * Tích hợp Laravel Echo để nhận thông báo real-time.
 */

// ============================================================
// NOTIFICATION HUB - Alpine.js Component
// ============================================================
document.addEventListener('alpine:init', () => {

    Alpine.data('notificationHub', (userId, initialUnreadCount = 0) => ({
        open: false,
        unreadCount: initialUnreadCount,
        notifications: [],
        loading: false,

        init() {
            this.loadNotifications();
            this.listenForRealtime(userId);
        },

        /**
         * Load 5 thông báo mới nhất từ API.
         */
        async loadNotifications() {
            this.loading = true;
            try {
                const response = await axios.get('/notifications', {
                    params: { per_page: 5 }
                });
                if (response.data.status === 'success') {
                    this.notifications = response.data.data.data.slice(0, 5);
                    this.unreadCount = response.data.unread_count;
                }
            } catch (error) {
                console.error('Lỗi tải thông báo:', error);
            } finally {
                this.loading = false;
            }
        },

        /**
         * Đăng ký kênh Echo private để lắng nghe notification real-time.
         */
        listenForRealtime(userId) {
            if (!window.Echo) {
                console.warn('Laravel Echo chưa được khởi tạo.');
                return;
            }

            window.Echo.private(`App.Models.User.${userId}`)
                .notification((notification) => {
                    // Tăng counter
                    this.unreadCount++;

                    // Thêm vào đầu danh sách dropdown
                    const notifItem = {
                        id: notification.id,
                        data: notification,
                        read_at: null,
                        created_at: new Date().toISOString(),
                    };
                    this.notifications.unshift(notifItem);

                    // Giới hạn 5 thông báo trong dropdown
                    if (this.notifications.length > 5) {
                        this.notifications = this.notifications.slice(0, 5);
                    }

                    // Hiển thị Toast popup
                    window.dispatchEvent(new CustomEvent('show-notification-toast', {
                        detail: {
                            id: notification.id || Date.now(),
                            title: notification.title || 'Thông báo mới',
                            body: notification.body || '',
                            icon: notification.icon || 'fas fa-bell',
                            url: notification.url || '#',
                        }
                    }));
                });
        },

        /**
         * Click vào thông báo: đánh dấu đã đọc rồi chuyển hướng.
         */
        async handleNotifClick(notif) {
            if (!notif.read_at) {
                try {
                    const response = await axios.post(`/notifications/${notif.id}/read`);
                    if (response.data.status === 'success') {
                        notif.read_at = new Date().toISOString();
                        this.unreadCount = response.data.unread_count;
                    }
                } catch (error) {
                    console.error('Lỗi đánh dấu đã đọc:', error);
                }
            }

            const url = notif.data?.url || '#';
            if (url && url !== '#') {
                window.location.href = url;
            }
        },

        /**
         * Đánh dấu tất cả thông báo là đã đọc.
         */
        async markAllAsRead() {
            try {
                const response = await axios.post('/notifications/read-all');
                if (response.data.status === 'success') {
                    this.unreadCount = 0;
                    this.notifications.forEach(n => {
                        n.read_at = new Date().toISOString();
                    });
                }
            } catch (error) {
                console.error('Lỗi đánh dấu tất cả đã đọc:', error);
            }
        },

        /**
         * Lấy CSS class cho icon dựa trên loại thông báo (Tailwind/Cyber variant).
         */
        getIconClasses(notif) {
            const colorMap = {
                'new_lecture': 'bg-brand/20 border-brand text-brand',
                'discussion_replied': 'bg-cyan-500/20 border-cyan-500 text-cyan-400',
                'payout_approved': 'bg-green-500/20 border-green-500 text-green-400',
                'fraud_risk_alert': 'bg-red-500/20 border-red-500 text-red-400',
            };
            return colorMap[notif.data?.type] || 'bg-brand/20 border-brand text-brand';
        },

        /**
         * Lấy Bootstrap color cho icon (Bootstrap variant).
         */
        getBootstrapColor(notif) {
            const colorMap = {
                'new_lecture': 'primary',
                'discussion_replied': 'info',
                'payout_approved': 'success',
                'fraud_risk_alert': 'danger',
            };
            return colorMap[notif.data?.type] || 'primary';
        },

        /**
         * Tính thời gian tương đối.
         */
        timeAgo(dateStr) {
            if (!dateStr) return '';
            const now = new Date();
            const date = new Date(dateStr);
            const diff = Math.floor((now - date) / 1000);

            if (diff < 60) return 'Vừa xong';
            if (diff < 3600) return Math.floor(diff / 60) + ' phút trước';
            if (diff < 86400) return Math.floor(diff / 3600) + ' giờ trước';
            if (diff < 604800) return Math.floor(diff / 86400) + ' ngày trước';
            return date.toLocaleDateString('vi-VN');
        },

        /**
         * Cắt ngắn chuỗi.
         */
        truncateText(text, maxLength) {
            if (!text) return '';
            return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
        },
    }));

    // ============================================================
    // NOTIFICATION TOAST - Alpine.js Component
    // ============================================================
    Alpine.data('notificationToast', () => ({
        toasts: [],

        init() {
            window.addEventListener('show-notification-toast', (event) => {
                this.addToast(event.detail);
            });
        },

        addToast(data) {
            const toast = {
                id: data.id || Date.now(),
                title: data.title || 'Thông báo mới',
                body: data.body || '',
                icon: data.icon || 'fas fa-bell',
                url: data.url || '#',
                visible: true,
                progress: 100,
            };

            this.toasts.push(toast);

            // Animate progress bar (5 giây)
            const duration = 5000;
            const interval = 50;
            const steps = duration / interval;
            const decrement = 100 / steps;

            const timer = setInterval(() => {
                const t = this.toasts.find(t => t.id === toast.id);
                if (!t) {
                    clearInterval(timer);
                    return;
                }
                t.progress -= decrement;
                if (t.progress <= 0) {
                    clearInterval(timer);
                    this.dismissToast(toast.id);
                }
            }, interval);
        },

        dismissToast(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) {
                toast.visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }
        },
    }));
});
