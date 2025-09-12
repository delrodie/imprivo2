import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['menu'];

    connect() {
        console.log('Dropdown controller connected');
    }

    toggle(event) {
        event.preventDefault();
        this.menuTarget.classList.toggle('show');
    }

    // Gère la fermeture du dropdown lorsqu'on clique en dehors
    hide(event) {
        if (!this.element.contains(event.target) && this.menuTarget.classList.contains('show')) {
            this.menuTarget.classList.remove('show');
        }
    }
}
