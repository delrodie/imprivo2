import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["form"]

    submit(event) {
        event.preventDefault()
        const form = this.formTarget
        const submitBtn = event.submitter || form.querySelector("#sendBtn")
        const cancelBtn = form.querySelector("#cancelBtn")

        if (!submitBtn) {
            console.warn("Aucun bouton de soumission trouvé dans le formulaire")
            return
        }

        // Feedback immédiat
        submitBtn.disabled = true
        if (cancelBtn) cancelBtn.disabled = true
        const originalHtml = submitBtn.innerHTML
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Sauvegarde...`

        // Nettoyer les champs numériques avant envoi (ex: montant avec séparateurs)
        form.querySelectorAll("input[data-numeric]").forEach(input => {
            if (input.value) {
                // Supprimer tous les espaces et séparateurs de milliers
                input.value = input.value.replace(/\s/g, "").replace(/,/g, "")
            }
        })

        // Préparer FormData et inclure la valeur du bouton cliqué
        const formData = new FormData(form)
        if (submitBtn && submitBtn.name) {
            formData.append(submitBtn.name, submitBtn.value)
        }

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    form.reset()

                    // Fermer l’offcanvas si présent
                    const offcanvasElement = document.getElementById("offcanvasDevis")
                    if (offcanvasElement) {
                        const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement)
                        if (offcanvas) offcanvas.hide()
                    }

                    // Rafraîchir DataTables si dispo
                    if (window.$ && $.fn.dataTable) {
                        $('#tabList').DataTable().ajax.reload(null, false)
                    }
                } else if (data.form) {
                    // Réinjecter le formulaire avec erreurs
                    const body = document.querySelector("#offcanvasDevis .offcanvas-body")
                    if (body) body.innerHTML = data.form
                }
            })
            .catch(err => alert("Erreur : " + err.message))
            .finally(() => {
                submitBtn.disabled = false
                if (cancelBtn) cancelBtn.disabled = false
                submitBtn.innerHTML = originalHtml
            })
    }
}
