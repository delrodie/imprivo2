import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["sidebar"]

    toggle() {
        if (this.hasSidebarTarget) {
            this.sidebarTarget.classList.toggle("d-none")
        } else {
            console.warn("⚠️ Aucun sidebarTarget trouvé")
        }
    }
}
