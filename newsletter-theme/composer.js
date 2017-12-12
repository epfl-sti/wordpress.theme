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
    // Clean up UI that we won't be using
    var button_row = $("tr", $("form > table"))[0];
    $('.button-primary', button_row).filter((unused_index, e) => {
        var elt = $(e),
            onclick = elt.attr("onclick");
        return (onclick && onclick.includes("'save'"));
    }).remove()
    $('img', button_row).filter((unused_index, e) => {
        var elt = $(e),
            src = elt.attr("src");
        return (src.endsWith("arrow.png"));
    }).remove()
});
