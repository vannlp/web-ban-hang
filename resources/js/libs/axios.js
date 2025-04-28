// resources/js/libs/axios.js
import axios from 'axios'

const axiosInstance = axios.create({
//   baseURL: '/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  withCredentials: true,
  timeout: 10000
})

// Optional: Interceptors for auth or logging
axiosInstance.interceptors.response.use(
  response => response,
  error => {
    console.error('Axios error:', error)
    return Promise.reject(error)
  }
)

export default axiosInstance