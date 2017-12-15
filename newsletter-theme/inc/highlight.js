/**
 * Highlight the search terms in a long string (e.g. article body)
 */

import _ from "lodash"
import assert from "assert"

export default function highlightKeywordHTML (text, kw, opts) {
    const defaultElision = () => "[...]",
        defaultHighlight = (word) => "<b>" + word + "</b>"
    opts = _.extend(
        {
            elide: defaultElision,
            highlight: defaultHighlight,
            contextWords: 4
        },
        opts)

    let shingles = text.split(new RegExp("(" + RegExp.escape(kw) + ")", "i"))
    assert.equal(shingles.length % 2, 1)

    let retval=""

    let whereToElide = "begin"
    while(shingles.length >= 2) {
        let [context, found] = shingles.splice(0, 2)
        retval+= _elideWords(context, whereToElide, opts) +
            opts.highlight(found)
        whereToElide = "middle"  // In case it was still "begin"
    }

    assert.equal(shingles.length, 1)
    retval += _elideWords(shingles[0], "end", opts)

    return retval
}

/**
 * Remove everything in text, except the few first and/or last words.
 */
export /* for tests */
function _elideWords (text, where, opts) {
    let shingles = text.split(/(\W+)/)
    assert.equal(shingles.length % 2, 1)
    if (shingles.length < 2) return shingles.join("")

    // shingles is an odd-numbered list of fragments alternating
    // punctuation and "words".
    // However, the first and last "words" were glued either to the
    // search keyword or to the beginning / end of the string passed
    // to highlightKeywordHTML so they don't count (e.g. when
    // searching for a singular and finding a plural, the first "word"
    // in the string passed to _elideWords will just be the final
    // "s").
    let beginShingles = where === "begin" ? [] :
        shingles.splice(0, 2 * opts.contextWords + 2)
    let endShingles = where === "end" ? [] :
        shingles.splice(-(2 * opts.contextWords + 2))

    return shingles.length ?
        beginShingles.join("") + opts.elide(shingles) + endShingles.join("") :
        beginShingles.concat(endShingles).join("")
}
