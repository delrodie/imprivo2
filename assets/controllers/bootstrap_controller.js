// assets/controllers/bootstrap_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["sidebar"]

    connect() {
        this._onTurboLoad = this._onTurboLoad.bind(this)
        this._onBeforeCache = this._onBeforeCache.bind(this)

        document.addEventListener('turbo:load', this._onTurboLoad)
        document.addEventListener('turbo:before-cache', this._onBeforeCache)

        this._initAll()
        console.debug('[bootstrap_controller] connected')
    }

    disconnect() {
        document.removeEventListener('turbo:load', this._onTurboLoad)
        document.removeEventListener('turbo:before-cache', this._onBeforeCache)
        // ne pas supprimer ici les instances si tu veux que bootstrap gère via data-api
    }

    _onTurboLoad() {
        this._initAll()
        console.debug('[bootstrap_controller] turbo:load - initAll')
    }

    _onBeforeCache() {
        // éviter de garder des instances attachées sur des éléments qui seront mis en cache
        this._disposeBootstrapInstances()
    }

    _initAll() {
        this._initSidebar()
        this._initBootstrapComponents()
    }

    /* ---------- sidebar logic ---------- */
    _initSidebar() {
        this.sidebarEl = this.hasSidebarTarget ? this.sidebarTarget : document.querySelector('[data-bootstrap-target="sidebar"]')
        this.toggleButton = document.querySelector('#sidebar-toggle-desktop')

        if (this.toggleButton) {
            if (this._toggleHandler) this.toggleButton.removeEventListener('click', this._toggleHandler)
            this._toggleHandler = this.toggleSidebar.bind(this)
            this.toggleButton.addEventListener('click', this._toggleHandler)
        } else {
            console.warn('[bootstrap_controller] toggle button not found (#sidebar-toggle-desktop)')
        }

        // restauration état (optionnel)
        try {
            const collapsed = localStorage.getItem('imprivo-sidebar-collapsed')
            if (collapsed === 'true') {
                this.sidebarEl && this.sidebarEl.classList.add('d-none')
                document.body.classList.add('sidebar-collapsed')
            } else {
                this.sidebarEl && this.sidebarEl.classList.remove('d-none')
                document.body.classList.remove('sidebar-collapsed')
            }
        } catch(e) { /* ignore */ }
    }

    toggleSidebar() {
        if (!this.sidebarEl) return
        this.sidebarEl.classList.toggle('d-none')
        document.body.classList.toggle('sidebar-collapsed')
        try { localStorage.setItem('imprivo-sidebar-collapsed', this.sidebarEl.classList.contains('d-none')) } catch(e) {}
    }

    /* ---------- bootstrap components init ---------- */
    _initBootstrapComponents() {
        if (!window.bootstrap) {
            console.debug('[bootstrap_controller] window.bootstrap not present → skipping bootstrap init')
            return
        }

        // instantiate dropdowns if not already done
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
            if (!el.__bootstrapDropdown) {
                try {
                    el.__bootstrapDropdown = new window.bootstrap.Dropdown(el)
                } catch(e) { console.warn('dropdown init failed', e) }
            }
        })

        // instantiate offcanvas if not already done
        document.querySelectorAll('.offcanvas').forEach(el => {
            if (!el.__bootstrapOffcanvas) {
                try {
                    el.__bootstrapOffcanvas = new window.bootstrap.Offcanvas(el)
                } catch(e) { console.warn('offcanvas init failed', e) }
            }
        })
    }

    _disposeBootstrapInstances() {
        // dispose dropdowns
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
            if (el.__bootstrapDropdown) {
                try { el.__bootstrapDropdown.dispose() } catch(e) {}
                delete el.__bootstrapDropdown
            }
        })
        // dispose offcanvas
        document.querySelectorAll('.offcanvas').forEach(el => {
            if (el.__bootstrapOffcanvas) {
                try { el.__bootstrapOffcanvas.dispose() } catch(e) {}
                delete el.__bootstrapOffcanvas
            }
        })
    }
}
