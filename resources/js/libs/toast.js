class ToastManager {
    constructor() {
        this.toastRef = null
    }

    register(ref) {
        this.toastRef = ref
    }

    error(message) {
        this.show(message, 'error')
    }

    success(message) {
        this.show(message, 'success')
    }

    warning(message) {
        this.show(message, 'warning')
    }

    info(message) {
        this.show(message, 'info')
    }

    show(message, color = 'info') {
        if (this.toastRef) {
            this.toastRef.showToast(message, color)
        } else {
            console.error('Toast not registered yet!')
        }
    }
}

// Xuất ra instance dùng chung toàn hệ thống
const toastManager = new ToastManager()
export default toastManager
