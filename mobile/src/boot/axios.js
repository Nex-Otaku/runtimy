import {boot} from 'quasar/wrappers'
import axios from 'axios'

axios.defaults.withCredentials = true

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({baseURL: process.env.API_URL});

const routerWrapper = {
  router: null,
}

// Add a response interceptor
api.interceptors.response.use(function (response) {
  // Any status code that lie within the range of 2xx cause this function to trigger
  // Do something with response data

  return response;
}, function (error) {
  // Any status codes that falls outside the range of 2xx cause this function to trigger
  // Do something with response error

  if (error.response) {
    // The request was made and the server responded with a status code
    // that falls out of the range of 2xx
    // console.log(error.response.data);
    // console.log(error.response.status);
    // console.log(error.response.headers);

    if (error.response.status === 401) {
      const router = routerWrapper.router;
      const currentRouteName = router.currentRoute.value.name;

      if (currentRouteName !== 'login-phone') {
        router.push({name: 'login-phone'});
      }

      return Promise.reject(error);
    }
  } else if (error.request) {
    // The request was made but no response was received
    // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
    // http.ClientRequest in node.js
    console.log('No response form server', error.request);

    return Promise.reject(error);
  }

  // Something happened in setting up the request that triggered an Error
  console.log('Unknown Axios Error', error.message);

  return Promise.reject(error);
});

export default boot(({app, router}) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  // Пишем ссылку на роутер
  routerWrapper.router = router;

  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export {api}
