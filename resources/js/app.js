
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import 'jquery-form';
import 'jquery-ui/ui/effects/effect-slide'
import 'jquery-ui/ui/widgets/progressbar'
import 'jquery-ui/ui/widgets/autocomplete';
import 'jquery-validation';
import 'jquery-confirm';
const lazy = require('jquery-lazy');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});
