/**
 * WPajax: send an AJAX request to WordPress
 *
 * @see https://codex.wordpress.org/AJAX_in_Plugins
 */
import _ from "lodash"

function _parse_opts(action, url_or_opts, data) {
    if (arguments.length === 2) {
        data = url_or_opts
        url_or_opts = undefined
    }
    const opts = (
        typeof(url_or_opts) === "string"    ? { url: url_or_opts } :
        typeof(url_or_opts) === "undefined" ? {}                   :
        url_or_opts
    )
    return [ action, opts, data ]
}

function WPajax(/*action, url_or_opts?, data */) {
    const [ action, opts, data ] = _parse_opts.apply({}, arguments)
    var url = window.epflsti_newsletter_composer.ajaxurl;
    url = url + ((url.indexOf("?") !== -1) ? "&" : "?")
        + "action=" + action
        // See docstring of script_pass_params() in ../hook.php for explanations
        + "&_ajax_nonce=" + window.epflsti_newsletter_composer.nonce;
    var ajax = jQuery.ajax(
        _.extend(
            {
                url: url,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                dataType: "json"  // Expected in return
            }, opts))
    ajax.then = ajax.done.bind(ajax)
    ajax.catch = ajax.fail.bind(ajax)
    return ajax
}

export default WPajax

WPajax.get = function(/* action, url_or_opts?, data */) {
    const [ action, opts, data ] = _parse_opts.apply({}, arguments);
    return WPajax(action, _.extend({type: "GET"}, opts), data);
}

WPajax.post = function(/* action, url_or_opts?, data */) {
    const [ action, opts, data ] = _parse_opts.apply({}, arguments);
    return WPajax(_.extend({type: "POST"}, opts), data);
}
