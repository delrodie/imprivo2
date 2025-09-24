// assets/controllers/notification_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    submit(event) {
        event.preventDefault()
        const form = event.target
        const submitter = event.submitter // bouton cliqué
        const formData = new FormData(form)
        if (submitter?.name) {
            formData.append(submitter.name, submitter.value)
        }


        fetch(form.action, {
            method: form.method,
            // method: "POST",
            // body: new FormData(form),
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(response => {
                if (!response.ok) throw new Error("Erreur serveur")
                return response.text()
            })
            .then(() => {
                // 🔄 Message personnalisé ou fallback
                const message = submitter?.dataset.message || "Formulaire soumis avec succès"
                this.showToast(message, "success", "check-circle")

                // 🔄 Redirection personnalisée
                const redirectUrl = submitter?.dataset.redirect || form.dataset.redirect
                if (redirectUrl) {
                    setTimeout(() => window.location.href = redirectUrl, 800)
                }
            })
            .catch(error => {
                console.error(error)
                this.showToast("Erreur lors de la soumission", "danger", "alert-triangle")
            })
    }

    showToast(message, type = "info", icon = "info") {
        const container = document.getElementById("toast-container")
        const toast = document.createElement("div")
        toast.className = `toast align-items-center border-0 shadow-sm mb-2 bg-${type}`
        toast.setAttribute("role", "alert")
        toast.setAttribute("aria-live", "assertive")
        toast.setAttribute("aria-atomic", "true")

        toast.innerHTML = `
            <div class="d-flex align-items-center text-white p-2">
                <i data-lucide="${icon}" class="me-2"></i>
                <div class="toast-body flex-grow-1">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `

        container.appendChild(toast)
        lucide.createIcons()

        const bsToast = new bootstrap.Toast(toast, { delay: 3000 })
        bsToast.show()

        toast.addEventListener("hidden.bs.toast", () => toast.remove())
    }
}
