/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue');

window.axios = require('axios');

import { Form, HasError, AlertError,  AlertErrors,  AlertSuccess } from 'vform';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)


import Swal from 'sweetalert2'
window.Swal = Swal;

// import plugin
import VueToastr from "vue-toastr";
// use plugin
Vue.use(VueToastr, {
  defaultTimeout: 3000,
  defaultProgressBar: true,
});

Vue.config.productionTip = false;




/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('create-user', require('./components/CreateUser.vue'));


// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */

// const app = new Vue({
//     el: '#app',
// });
