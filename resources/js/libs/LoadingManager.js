class LoadingManager {
  constructor() {
    this.loadingRef = null
  }

  register(ref) {
    this.loadingRef = ref
  }

  show() {
    if (this.loadingRef) {
      this.loadingRef.show()
    } else {
      console.warn('Loading not registered!')
    }
  }

  hide() {
    if (this.loadingRef) {
      this.loadingRef.hide()
    } else {
      console.warn('Loading not registered!')
    }
  }
}

const loadingManager = new LoadingManager()
export default loadingManager
