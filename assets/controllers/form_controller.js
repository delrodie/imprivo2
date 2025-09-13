import { Controller } from "@hotwired/stimulus"

// Stimulus controller pour gérer les formulaires AJAX
export default class extends Controller {
    static targets = ["form"]

    connect() {
        console.log("FormController connecté")
    }

    submit(event) {
        event.preventDefault()
        const form = this.formTarget

        fetch(form.action, {
            method: form.method,
            body: new FormData(form),
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .then(response => {
                if (!response.ok) throw new Error("Erreur serveur")
                return response.json()
            })
            .then(data => {
                if (data.success) {
                    // Fermer l’offcanvas
                    const offcanvasElement = document.getElementById("offcanvasDevis")
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement)
                    offcanvas.hide()

                    // Recharger DataTables
                    const table = $('#tabList').DataTable()
                    table.ajax.reload()
                }
            })
            .catch(err => {
                alert("Une erreur est survenue : " + err.message)
            })
    }
}
