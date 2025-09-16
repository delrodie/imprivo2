import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    async toggle(event) {
        const checkbox = event.target
        const permissionId = checkbox.value
        const enabled = checkbox.checked ? "1" : "0"

        const urlParts = window.location.pathname.split("/")
        const employeId = urlParts[urlParts.length - 1]

        try {
            let bodyData

            if (permissionId) {
                // ✅ Cas classique : permission existe déjà
                bodyData = new URLSearchParams({
                    permissionId,
                    enabled
                })
            } else {
                // ✅ Cas nouveau : il faut créer la permission
                const module = checkbox.dataset.permissionModule
                const action = checkbox.dataset.permissionAction

                bodyData = new URLSearchParams({
                    module,
                    action,
                    enabled
                })
            }

            const response = await fetch(`/admin/user-permission/${employeId}/toggle`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: bodyData
            })

            if (!response.ok) {
                throw new Error("Erreur serveur")
            }

            const data = await response.json()
            if (data.success) {
                // 🔄 Mettre à jour l’ID si une nouvelle permission a été créée
                if (data.permissionId) {
                    checkbox.value = data.permissionId
                }
                this.showToast("Permission mise à jour avec succès", "success", "check-circle")
            } else {
                this.showToast(data.message || "Erreur lors de la mise à jour", "danger", "alert-triangle")
                checkbox.checked = !checkbox.checked
            }
        } catch (error) {
            console.error(error)
            this.showToast("Impossible de contacter le serveur", "danger", "wifi-off")
            checkbox.checked = !checkbox.checked
        }
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
