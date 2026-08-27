/**
 * AdminApp — Viata Luxe Guesthouse
 * SPA-style admin panel controller
 */
const AdminApp = {
    init(page) {
        this.currentPage = page;
        this.contentEl = document.getElementById('adminContent');
        this.pageTitleEl = document.getElementById('pageTitle');
        this.sidebar = document.getElementById('sidebar');
        this.overlay = document.getElementById('overlay');

        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileToggle = document.getElementById('mobileToggle');
        if (sidebarToggle) sidebarToggle.addEventListener('click', () => this.toggleSidebar());
        if (mobileToggle) mobileToggle.addEventListener('click', () => this.toggleSidebar());
        if (this.overlay) this.overlay.addEventListener('click', () => this.closeSidebar());

        // Load initial page content
        if (page) {
            this.loadPage(page);
        }
    },

    loadPage(path) {
        if (!this.contentEl) return;
        this.contentEl.innerHTML = '<div class="loading">Loading...</div>';

        fetch('/admin' + path, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => {
            if (!r.ok) throw new Error('Page load failed');
            return r.text();
        })
        .then(html => {
            // Extract content between admin-page div
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const adminPage = doc.querySelector('.admin-page');
            if (adminPage) {
                this.contentEl.innerHTML = adminPage.innerHTML;
            } else {
                this.contentEl.innerHTML = html;
            }
            this.updateTitle(path);
            this.initPageForms();
        })
        .catch(err => {
            this.contentEl.innerHTML = '<div class="card" style="padding:24px;text-align:center"><p>Failed to load page.</p></div>';
        });
    },

    updateTitle(path) {
        const titles = {
            '/pages': 'Pages', '/sections': 'Sections', '/apartments': 'Apartments',
            '/testimonials': 'Testimonials', '/gallery': 'Gallery', '/navigation': 'Navigation',
            '/faqs': 'FAQs', '/safari': 'Safari Activities', '/contact': 'Contact Submissions',
            '/settings': 'Settings', '/dashboard': 'Dashboard'
        };
        const key = Object.keys(titles).find(k => path.startsWith(k));
        if (this.pageTitleEl) this.pageTitleEl.textContent = titles[key] || path;
    },

    toggleSidebar() {
        this.sidebar.classList.toggle('open');
        this.overlay.classList.toggle('visible');
    },

    closeSidebar() {
        this.sidebar.classList.remove('open');
        this.overlay.classList.remove('visible');
    },

    initPageForms() {
        // Attach form handlers via event delegation
        this.contentEl.querySelectorAll('form[data-ajax]').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                this.submitForm(form);
            });
        });
        this.contentEl.querySelectorAll('[data-confirm]').forEach(el => {
            el.addEventListener('click', e => {
                if (!confirm(el.dataset.confirm)) e.preventDefault();
            });
        });
    },

    submitForm(form) {
        const formData = new FormData(form);
        const action = form.action || window.location.pathname;
        const method = form.method || 'POST';

        fetch(action, {
            method: method,
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (data.error) {
                this.showToast(data.error, 'error');
            } else {
                this.showToast('Saved successfully');
            }
        })
        .catch(() => this.showToast('Request failed', 'error'));
    },

    showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = 'admin-toast admin-toast-' + type;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 3000);
    }
};
