/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueObserveVisibility from 'vue-observe-visibility';
import mixins from './mixins';
window.Vue = require('vue').default;
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('media-library', require('./components/MediaLibrary/MediaLibrary.vue').default);
Vue.component('standalone-media-library', require('./components/MediaLibrary/StandaloneMediaLibrary.vue').default);
Vue.component('gallery-media-library', require('./components/MediaLibrary/GalleryMediaLibrary.vue').default);
// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueObserveVisibility)
Vue.extend({
    mixins: [mixins]
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
