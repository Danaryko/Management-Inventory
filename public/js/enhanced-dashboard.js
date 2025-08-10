// Enhanced Dashboard and Activity History JavaScript

class DashboardManager {
    constructor() {
        this.autoRefreshInterval = null;
        this.isAutoRefreshEnabled = false;
        this.init();
    }

    init() {
        this.setupAutoRefresh();
        this.setupExportButtons();
        this.setupMobileOptimizations();
        this.setupNotifications();
        this.setupCharts();
        this.setupRealTimeUpdates();
    }

    // Auto-refresh functionality for activities
    setupAutoRefresh() {
        const autoRefreshBtn = document.getElementById('auto-refresh-btn');
        const autoRefreshText = document.getElementById('auto-refresh-text');
        
        if (!autoRefreshBtn) return;
        
        autoRefreshBtn.addEventListener('click', () => {
            if (this.isAutoRefreshEnabled) {
                this.disableAutoRefresh(autoRefreshBtn, autoRefreshText);
            } else {
                this.enableAutoRefresh(autoRefreshBtn, autoRefreshText);
            }
        });
    }

    enableAutoRefresh(btn, text) {
        this.autoRefreshInterval = setInterval(() => this.refreshActivities(), 30000);
        this.isAutoRefreshEnabled = true;
        text.textContent = 'Auto Refresh ON';
        btn.classList.add('bg-green-50', 'text-green-700', 'border-green-300', 'auto-refresh-active');
        btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
        this.showNotification('Auto-refresh enabled', 'success');
    }

    disableAutoRefresh(btn, text) {
        clearInterval(this.autoRefreshInterval);
        this.isAutoRefreshEnabled = false;
        text.textContent = 'Auto Refresh';
        btn.classList.remove('bg-green-50', 'text-green-700', 'border-green-300', 'auto-refresh-active');
        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
        this.showNotification('Auto-refresh disabled', 'info');
    }

    async refreshActivities() {
        try {
            const formData = new FormData(document.querySelector('form'));
            const queryString = new URLSearchParams(formData).toString();
            
            const response = await fetch(`${window.location.pathname}?${queryString}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) throw new Error('Failed to refresh');
            
            const data = await response.json();
            const tableBody = document.getElementById('activities-table-body');
            
            if (tableBody && data.html) {
                tableBody.innerHTML = data.html;
                this.showNotification('Activities updated', 'success', 2000);
            }
        } catch (error) {
            console.error('Auto-refresh failed:', error);
            this.showNotification('Failed to refresh activities', 'error');
            this.disableAutoRefresh(
                document.getElementById('auto-refresh-btn'),
                document.getElementById('auto-refresh-text')
            );
        }
    }

    // Export functionality
    setupExportButtons() {
        const exportButtons = document.querySelectorAll('[data-export]');
        exportButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleExport(btn.dataset.export);
            });
        });

        // Add event listeners for export functions
        window.exportActivities = (format) => this.handleExport(format);
    }

    async handleExport(format) {
        const btn = event.target.closest('button');
        if (!btn) return;

        btn.classList.add('loading');
        
        try {
            const formData = new FormData(document.querySelector('form'));
            formData.append('export', format);
            const queryString = new URLSearchParams(formData).toString();
            
            // Create a temporary link for download
            const link = document.createElement('a');
            link.href = `${window.location.origin}/activities/export?${queryString}`;
            link.download = `activities_${format}_${new Date().toISOString().slice(0, 10)}.${format === 'excel' ? 'xlsx' : 'csv'}`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            this.showNotification(`Export started (${format.toUpperCase()})`, 'success');
        } catch (error) {
            console.error('Export failed:', error);
            this.showNotification('Export failed', 'error');
        } finally {
            btn.classList.remove('loading');
        }
    }

    // Mobile optimizations
    setupMobileOptimizations() {
        // Touch-friendly interactions
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');
            
            // Improve table scrolling on mobile
            const tables = document.querySelectorAll('.overflow-x-auto');
            tables.forEach(table => {
                table.style.webkitOverflowScrolling = 'touch';
            });
        }

        // Responsive table enhancements
        this.makeTablesResponsive();
        
        // Collapsible sidebar on mobile
        this.setupMobileSidebar();
    }

    makeTablesResponsive() {
        const tables = document.querySelectorAll('table');
        tables.forEach(table => {
            if (window.innerWidth < 768) {
                // Hide less important columns on mobile
                const cells = table.querySelectorAll('th:nth-child(3), td:nth-child(3)');
                cells.forEach(cell => cell.classList.add('hidden-mobile'));
            }
        });
    }

    setupMobileSidebar() {
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768) {
                const sidebar = document.querySelector('aside');
                const toggleBtn = document.querySelector('[data-sidebar-toggle]');
                
                if (sidebar && !sidebar.contains(e.target) && !toggleBtn?.contains(e.target)) {
                    // Close sidebar
                    if (window.sidebarOpen !== undefined) {
                        window.sidebarOpen = false;
                    }
                }
            }
        });
    }

    // Notification system
    setupNotifications() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notification-container')) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
    }

    showNotification(message, type = 'info', duration = 4000) {
        const container = document.getElementById('notification-container');
        if (!container) return;

        const notification = document.createElement('div');
        notification.className = `
            px-4 py-3 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full
            ${type === 'success' ? 'bg-green-500 text-white' : 
              type === 'error' ? 'bg-red-500 text-white' : 
              type === 'warning' ? 'bg-yellow-500 text-white' : 
              'bg-blue-500 text-white'}
        `;
        
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="ml-3 text-white hover:text-gray-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        container.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }

    // Dashboard charts (placeholder for future implementation)
    setupCharts() {
        // This would integrate with Chart.js or similar library
        // For now, just add some visual enhancements to stat cards
        const statCards = document.querySelectorAll('.dashboard-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // Real-time updates using Server-Sent Events (placeholder)
    setupRealTimeUpdates() {
        // In a production environment, this would connect to SSE or WebSocket
        // For now, we'll use periodic checks for new activities
        if (document.querySelector('.activities-table')) {
            this.setupActivityNotifications();
        }
    }

    setupActivityNotifications() {
        // Check for new activities every 2 minutes
        setInterval(async () => {
            if (!this.isAutoRefreshEnabled && document.hidden === false) {
                try {
                    const response = await fetch('/activities?check_new=1', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.hasNew) {
                            this.showNotification('New activities available. Click refresh to update.', 'info', 6000);
                        }
                    }
                } catch (error) {
                    // Silently fail for background checks
                }
            }
        }, 120000); // 2 minutes
    }

    // Utility methods
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
}

// Dashboard performance monitoring
class PerformanceMonitor {
    constructor() {
        this.startTime = performance.now();
        this.setupMonitoring();
    }

    setupMonitoring() {
        // Monitor page load performance
        window.addEventListener('load', () => {
            const loadTime = performance.now() - this.startTime;
            console.log(`Dashboard loaded in ${loadTime.toFixed(2)}ms`);
            
            // Report slow loads
            if (loadTime > 3000) {
                console.warn('Dashboard load time exceeded 3 seconds');
            }
        });

        // Monitor API response times
        this.interceptFetch();
    }

    interceptFetch() {
        const originalFetch = window.fetch;
        window.fetch = async (...args) => {
            const startTime = performance.now();
            try {
                const response = await originalFetch(...args);
                const endTime = performance.now();
                const duration = endTime - startTime;
                
                console.log(`API call to ${args[0]} took ${duration.toFixed(2)}ms`);
                
                if (duration > 5000) {
                    console.warn(`Slow API response detected: ${args[0]} (${duration.toFixed(2)}ms)`);
                }
                
                return response;
            } catch (error) {
                console.error(`API call failed: ${args[0]}`, error);
                throw error;
            }
        };
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new DashboardManager();
    new PerformanceMonitor();
    
    // Setup keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + R for refresh
        if ((e.ctrlKey || e.metaKey) && e.key === 'r' && document.querySelector('.activities-table')) {
            e.preventDefault();
            const refreshBtn = document.getElementById('auto-refresh-btn');
            if (refreshBtn) {
                refreshBtn.click();
            }
        }
        
        // Esc to close modals/notifications
        if (e.key === 'Escape') {
            document.querySelectorAll('.notification').forEach(n => n.remove());
        }
    });
});

// Handle window resize for responsive features
window.addEventListener('resize', () => {
    if (window.dashboardManager) {
        window.dashboardManager.makeTablesResponsive();
    }
});

// Export for global access
window.DashboardManager = DashboardManager;