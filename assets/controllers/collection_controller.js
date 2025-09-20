import { Controller } from "@hotwired/stimulus";

// Connects to data-controller="collection"
export default class extends Controller {
    static targets = ["container"]

    connect() {
        this.index = this.containerTarget.children.length;
        console.log("collection connectée")
    }

    add(event) {
        event.preventDefault()
        const prototype = this.containerTarget.dataset.prototype
        const index = this.containerTarget.dataset.index || this.containerTarget.querySelectorAll("tbody tr").length
        const newForm = prototype.replace(/__name__/g, index)

        this.containerTarget.querySelector("tbody").insertAdjacentHTML('beforeend', newForm)
        this.containerTarget.dataset.index = parseInt(index) + 1

        // 🔥 Déclenche recalcul auto
        this.containerTarget.dispatchEvent(new Event("ligne:changed", { bubbles: true }))
    }

    remove(event) {
        event.preventDefault()
        event.target.closest("tr").remove()

        // 🔥 Déclenche recalcul après suppression
        this.containerTarget.dispatchEvent(new Event("ligne:changed", { bubbles: true }))
    }
}
