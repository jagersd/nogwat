require('./bootstrap');

window.Vue = require('vue').default;

import App from './app.vue'
import MainLayout from './components/MainLayout'

const app = new Vue({
    el: '#app',
    components: { App, MainLayout }
});
