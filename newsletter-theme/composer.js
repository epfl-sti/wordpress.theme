import Vue from 'vue'
import Composer from './vue/Composer.vue'

jQuery(function($){
    // As per the instructions in node_modules/vueify/README.md:
    new Vue({
        el: '#vuenewslettercomposer',
        render: function (createElement) {
            return createElement(Composer)
        }
    })
});
