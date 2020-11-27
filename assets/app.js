/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import Vue from 'vue'
import App from './js/App.vue'
import Show from './js/Show.vue'
import moment from 'moment'

Vue.config.productionTip = false
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).utcOffset(0).format('DD/MM/YYYY HH:mm')
    }
});

new Vue({
    el: '#app',
    render: h => h(App,Show)
})
