/**
 * WPajax: send an AJAX request to WordPress
 *
 * @see https://codex.wordpress.org/AJAX_in_Plugins
 */
import _ from "lodash"

function _parse_opts(url_or_opts, data) {
    if (arguments.length === 1) {
        data = url_or_opts
        url_or_opts = undefined
    }
    const opts = (
        typeof(url_or_opts) === "string"    ? { url: url_or_opts } :
        typeof(url_or_opts) === "undefined" ? {}                   :
        url_or_opts
    )
    return [ opts, data ]
}

function WPajax(/* optional__url_or_opts, data */) {
    const [ opts, data ] = _parse_opts.apply({}, arguments)
    if (! data._ajax_nonce) {
        // See docstring of hook_xsrf_nonce() in ../hook.php for explanations
        data._ajax_nonce = window.epflsti_newsletter_composer.nonce;
    }
    var ajax = jQuery.ajax(
        _.extend(
            {
                url: window.ajaxurl,
                dataType: "json",
                data: data,
                type: "POST"
            }, opts))
    ajax.then = ajax.done.bind(ajax)
    ajax.catch = ajax.fail.bind(ajax)
    return ajax
}

export default WPajax

WPajax.get = function(/* optional__url_or_opts, data */) {
    const [ opts, data ] = _parse_opts.apply({}, arguments);
    return WPajax(_.extend({type: "GET"}, opts), data);
}

WPajax.post = function(/* optional__url_or_opts, data */) {
    const [ opts, data ] = _parse_opts.apply({}, arguments);
    return WPajax(_.extend({type: "POST"}, opts), data);
}
