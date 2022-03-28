import Vue from "vue";

require('./bootstrap');
window.Vue = require('vue');

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
Vue.use(VueToast, {
    // One of the options
    position: 'top-right',
});


import App from './components/layout';
import router from './routes';
// import helper from './helper';

const app = new Vue({
    el: '#app',
    router,
    render: h => h(App),
});
