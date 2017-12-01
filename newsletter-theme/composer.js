import Vue from 'vue'
import Hello from './hello.vue'

jQuery(function($){
    // As per the instructions in node_modules/vueify/README.md:
    new Vue({
        el: '#vuenewslettercomposer',
        render: function (createElement) {
            return createElement(Hello)
        }
    })
});
