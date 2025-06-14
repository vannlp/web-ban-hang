import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import toastManager from './toast'
import loadingManager from './LoadingManager'

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
    // xử lý lỗi validate 
    let message = '';
    let status = error?.status;
    if(status == 422) {
      let errors = error?.response?.data?.errors;
      if(errors) {
        let messages = Object.values(errors).flat();
        
        message = messages.join(', ');
        
        toastManager.error(message);
        
      }
      loadingManager.hide();
      return Promise.reject(error)
    } 
    
    
    message = error?.response?.data?.message;
    // console.error('Axios error:', message)
    loadingManager.hide();
    toastManager.error(message);
    return Promise.reject(error)
  }
)

export default axiosInstance
