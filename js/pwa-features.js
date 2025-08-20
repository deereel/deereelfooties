// Progressive Web App features
class PWAFeatures {
    constructor() {
        this.init();
    }
    
    init() {
        this.addInstallPrompt();
        this.enablePushNotifications();
        this.addOfflineSupport();
    }
    
    addInstallPrompt() {
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            this.showInstallButton();
        });
        
        document.addEventListener('click', async (e) => {
            if (e.target.id === 'install-app-btn') {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    this.hideInstallButton();
                }
            }
        });
    }
    
    showInstallButton() {
        const installHTML = `
            <div id="install-banner" class="alert alert-primary alert-dismissible fade show position-fixed" 
                 style="top: 70px; right: 20px; z-index: 1000; max-width: 300px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-mobile-alt me-2"></i>
                    <div class="flex-grow-1">
                        <strong>Install DeeReel App</strong>
                        <div class="small">Get faster access and offline browsing!</div>
                    </div>
                </div>
                <button id="install-app-btn" class="btn btn-sm btn-primary mt-2 w-100">Install Now</button>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', installHTML);
    }
    
    hideInstallButton() {
        const banner = document.getElementById('install-banner');
        if (banner) banner.remove();
    }
    
    async enablePushNotifications() {
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                this.showNotificationOptIn();
            }
        }
    }
    
    showNotificationOptIn() {
        const notifHTML = `
            <div id="notification-banner" class="alert alert-info alert-dismissible fade show position-fixed" 
                 style="bottom: 20px; left: 20px; z-index: 1000; max-width: 300px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bell me-2"></i>
                    <div>
                        <strong>Stay Updated!</strong>
                        <div class="small">Get notified about new arrivals and exclusive deals</div>
                    </div>
                </div>
                <button id="enable-notifications" class="btn btn-sm btn-info mt-2 w-100">Enable Notifications</button>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', notifHTML);
        
        document.getElementById('enable-notifications').addEventListener('click', () => {
            this.subscribeToNotifications();
            document.getElementById('notification-banner').remove();
        });
    }
    
    async subscribeToNotifications() {
        try {
            const registration = await navigator.serviceWorker.ready;
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: 'your-vapid-public-key' // Replace with actual VAPID key
            });
            
            // Send subscription to server
            await fetch('/api/push-subscription.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(subscription)
            });
            
            alert('Notifications enabled! You\'ll receive updates about new products and deals.');
        } catch (error) {
            console.error('Failed to subscribe to notifications:', error);
        }
    }
    
    addOfflineSupport() {
        window.addEventListener('online', () => {
            this.showConnectionStatus('back online', 'success');
        });
        
        window.addEventListener('offline', () => {
            this.showConnectionStatus('offline', 'warning');
        });
    }
    
    showConnectionStatus(status, type) {
        const statusHTML = `
            <div class="alert alert-${type} position-fixed" style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 1001;">
                <i class="fas fa-wifi me-2"></i>You are ${status}
            </div>
        `;
        
        const statusEl = document.createElement('div');
        statusEl.innerHTML = statusHTML;
        document.body.appendChild(statusEl);
        
        setTimeout(() => statusEl.remove(), 3000);
    }
}

// Initialize PWA features
document.addEventListener('DOMContentLoaded', () => {
    new PWAFeatures();
});