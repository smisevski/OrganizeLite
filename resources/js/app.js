
/** Javascript dependencies & libs */
require('./bootstrap');

window.Vue = require('vue');
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
Vue.use(Vuetify);

/** Eg. ./components/ExampleComponent.vue -> <example-component></example-component> */
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('root', require('./components/root.vue').default);
Vue.component('top-navigation', require('./components/top-navigation.vue').default);
Vue.component('navigation-drawer', require('./components/navigation-drawer.vue').default);

/** VIEWMODELS */
Vue.component('clients', require('./components/viewmodels/clients.vue').default);
Vue.component('products', require('./components/viewmodels/products.vue').default);

window.csrf_token = "{{ csrf_token() }}"
/**
Instance and js scaffolding for the project
 */

const app = new Vue({
    el: '#app'
});
