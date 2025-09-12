import { Controller } from '@hotwired/stimulus';

export default class extends Controller{
    static targets = ['sidebar', 'mainContent'];

    connect() {
        console.log('Sidebar controller connected');
    }

    toggle() {
        const desktopSidebar = this.sidebarTargets.find(el => el.dataset.type === 'desktop');
        const mainContent = this.mainContentTarget;

        if(desktopSidebar){
            desktopSidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
        }
    }
}
