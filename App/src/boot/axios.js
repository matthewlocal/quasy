import axios from 'axios'

const axiosInstance = axios.create({
  baseURL: 'http://gomedia.local/api/'
})

export default ({ Vue }) => {  Vue.prototype.$axios = axiosInstance
}
export { axiosInstance }
