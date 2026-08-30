/**
 * AdminApp — Viata Luxe Guesthouse
 * SPA-style admin panel controller
 */
const AdminApp = {
    init(page, adminBase) {
        this.currentPage = page;
        this.adminBase = adminBase || '/admin';
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

        // Intercept sidebar link clicks for SPA navigation
        document.querySelectorAll('.sidebar-link[href]').forEach(link => {
            const href = link.getAttribute('href');
            if (href && href.includes('/admin/')) {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const adminPath = href.replace(/^.*\/admin/, '') || '/dashboard';
                    window.history.pushState({}, '', href);
                    this.loadPage(adminPath);
                    this.closeSidebar();
                });
            }
        });

        // Handle browser back/forward
        window.addEventListener('popstate', () => {
            const path = window.location.pathname.replace(/^.*\/admin/, '') || '/dashboard';
            this.loadPage(path);
        });
    },

    loadPage(path) {
        if (!this.contentEl) return;
        this.contentEl.innerHTML = '<div class="loading">Loading...</div>';

        fetch(this.adminBase + path, {
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
        // Intercept links in AJAX-loaded content for SPA navigation
        this.contentEl.querySelectorAll('a[href]').forEach(link => {
            const href = link.getAttribute('href');
            if (href && href.includes('/admin/')) {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const fullHref = href.startsWith('http') ? new URL(href).pathname : href;
                    const adminPath = fullHref.replace(/^.*\/admin/, '') || '/dashboard';
                    window.history.pushState({}, '', fullHref);
                    this.loadPage(adminPath);
                });
            }
        });
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
        let action = form.action || window.location.pathname;
        // Fix hardcoded /admin/ paths for subdirectory installs
        if (action.includes('/admin/') && !action.startsWith(this.adminBase)) {
            action = action.replace(/.*\/admin/, this.adminBase);
        }
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

/**
 * Image Browser — modal for browsing/uploading images.
 * Usage: ImageBrowser.open(callback) where callback receives the image path.
 */
const ImageBrowser = {
    modal: null,
    grid: null,
    callback: null,
    selectedPath: null,
    images: [],

    init() {
        this.modal = document.getElementById('imgBrowserModal');
        this.grid = document.getElementById('imgBrowserGrid');
        if (!this.modal) return;

        // Close buttons
        this.modal.querySelectorAll('[data-close-browser]').forEach(el => {
            el.addEventListener('click', () => this.close());
        });

        // Search
        const search = this.modal.querySelector('.img-browser-search');
        if (search) {
            let timer;
            search.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => this.loadImages(search.value), 250);
            });
        }

        // Directory filter
        const dirFilter = this.modal.querySelector('.img-browser-dir-filter');
        if (dirFilter) {
            dirFilter.addEventListener('change', () => {
                const searchVal = this.modal.querySelector('.img-browser-search').value;
                this.loadImages(searchVal, dirFilter.value);
            });
        }

        // Upload
        const uploadInput = this.modal.querySelector('.img-browser-upload-input');
        if (uploadInput) {
            uploadInput.addEventListener('change', (e) => this.handleUpload(e));
        }

        // Select button
        const selectBtn = this.modal.querySelector('.img-browser-select');
        if (selectBtn) {
            selectBtn.addEventListener('click', () => {
                if (this.selectedPath && this.callback) {
                    this.callback(this.selectedPath);
                    this.close();
                }
            });
        }

        // Keyboard: Escape to close
        this.modal.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.close();
        });
    },

    open(callback) {
        if (!this.modal) this.init();
        this.callback = callback;
        this.selectedPath = null;
        this.modal.classList.add('open');
        document.body.style.overflow = 'hidden';
        this.loadImages();
        this.modal.querySelector('.img-browser-search').value = '';
        this.modal.querySelector('.img-browser-select').disabled = true;
        // Focus search after open
        setTimeout(() => this.modal.querySelector('.img-browser-search').focus(), 100);
    },

    close() {
        if (!this.modal) return;
        this.modal.classList.remove('open');
        document.body.style.overflow = '';
        this.callback = null;
        this.selectedPath = null;
    },

    loadImages(search = '', dir = '') {
        if (!this.grid) return;
        this.grid.innerHTML = '<div class="img-browser-loading">Loading images...</div>';

        const params = new URLSearchParams();
        if (search) params.set('search', search);
        if (dir) params.set('dir', dir);

        fetch('/final website/admin/api/images.php?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            this.images = data.images || [];
            this.renderGrid(data.dirs || []);
        })
        .catch(() => {
            this.grid.innerHTML = '<div class="img-browser-empty">Failed to load images.</div>';
        });
    },

    renderGrid(dirs) {
        // Update directory filter options
        const dirFilter = this.modal.querySelector('.img-browser-dir-filter');
        if (dirFilter) {
            const current = dirFilter.value;
            dirFilter.innerHTML = '<option value="">All directories</option>';
            dirs.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                if (d === current) opt.selected = true;
                dirFilter.appendChild(opt);
            });
        }

        // Render image tiles
        this.grid.innerHTML = '';
        if (this.images.length === 0) {
            this.grid.innerHTML = '<div class="img-browser-empty">No images found.</div>';
            return;
        }

        this.images.forEach(img => {
            const tile = document.createElement('div');
            tile.className = 'img-browser-tile';
            tile.dataset.path = img.path;
            tile.title = img.path;
            tile.innerHTML = `
                <img src="/final website/${img.path}" alt="${img.name}" loading="lazy">
                <div class="img-browser-tile-name">${img.name}</div>
            `;
            tile.addEventListener('click', () => this.selectTile(tile));
            this.grid.appendChild(tile);
        });

        // Update count
        const count = this.modal.querySelector('.img-browser-count');
        if (count) count.textContent = this.images.length + ' image' + (this.images.length !== 1 ? 's' : '');
    },

    selectTile(tile) {
        this.grid.querySelectorAll('.img-browser-tile.selected').forEach(t => t.classList.remove('selected'));
        tile.classList.add('selected');
        this.selectedPath = tile.dataset.path;
        const selectBtn = this.modal.querySelector('.img-browser-select');
        if (selectBtn) selectBtn.disabled = false;
    },

    handleUpload(e) {
        const file = e.target.files[0];
        if (!file) return;
        const formData = new FormData();
        formData.append('image', file);

        this.grid.innerHTML = '<div class="img-browser-loading">Uploading...</div>';

        fetch('/final website/admin/api/images.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                this.loadImages();
            } else if (data.path) {
                // Select the newly uploaded image
                if (this.callback) {
                    this.callback(data.path);
                    this.close();
                }
            }
        })
        .catch(() => {
            alert('Upload failed');
            this.loadImages();
        });

        e.target.value = '';
    }
};

// Auto-init on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    ImageBrowser.init();
    // Wire browse buttons to image browser
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.browse-btn');
        if (btn) {
            e.preventDefault();
            const targetName = btn.dataset.target;
            if (!targetName) return;
            ImageBrowser.open((path) => {
                const input = btn.closest('.flex')?.querySelector(`input[name="${targetName}"]`)
                    || btn.parentElement.querySelector(`input[name="${targetName}"]`);
                if (input) {
                    input.value = path;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }
    });
});
