import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["form"]

    submit(event) {
        event.preventDefault()
        const form = this.formTarget
        const submitBtn = form.querySelector("#sendBtn")
        const cancelBtn = form.querySelector("#cancelBtn")

        // Feedback immédiat
        submitBtn.disabled = true
        cancelBtn.disabled = true
        const originalHtml = submitBtn.innerHTML
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Sauvegarde...`

        fetch(form.action, {
            method: form.method,
            body: new FormData(form),
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Fermer l’offcanvas
                    const offcanvasElement = document.getElementById("offcanvasDevis")
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement)
                    offcanvas.hide()

                    // Rafraîchir DataTables
                    $('#tabList').DataTable().ajax.reload(null, false)
                } else if (data.form) {
                    // Réinjecter le formulaire avec erreurs
                    document.querySelector("#offcanvasDevis .offcanvas-body").innerHTML = data.form
                }
            })
            .catch(err => alert("Erreur : " + err.message))
            .finally(() => {
                submitBtn.disabled = false
                cancelBtn.disabled = false
                submitBtn.innerHTML = originalHtml
            })
    }
}
