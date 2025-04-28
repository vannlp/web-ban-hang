import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import toastManager from './toast'

// Tạo instance
const axiosInstance = axios.create({
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  withCredentials: true,
  timeout: 10000,
})

// Gán CSRF token từ Inertia share vào header
const page = usePage()
// Lấy token từ meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
axiosInstance.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

// Optional: Interceptor log lỗi
axiosInstance.interceptors.response.use(
  response => response,
  error => {
    console.error('Axios error:', error)
    toastManager.error("Có lỗi xảy ra");
    return Promise.reject(error)
  }
)

export default axiosInstance
