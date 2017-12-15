import highlightKeywordHTML, { _elideWords } from "../../inc/highlight.js"
import _ from "lodash"
import assert from "assert"
import "core-js"

describe("highlightKeywordHTML", () => {
    function highlightProton(text, opts) {
        return highlightKeywordHTML(text, "proton",
                                    _.extend({contextWords: 1}, opts))
    }

    function assertHighlightProtonEqual (from, to, opts) {
        assert.equal(highlightProton(from, opts || {}), to)
    }

    it("elides one word", () => {
        assertHighlightProtonEqual(
            "Let's fire up the proton packs and have some fun",
            "[...] the <b>proton</b> packs [...]")
    })

    it("elides at the beginning, and keeps capitals and punctuation", () => {
        assertHighlightProtonEqual(
            "Protons are awesome, you should have some.",
            "<b>Proton</b>s are awesome, [...]",
            {contextWords: 2})
    })

    it("elides at the end", () => {
        assertHighlightProtonEqual(
            "Watch out for protons.",
            "[...] for <b>proton</b>s.")
    })

    it("elides multiple occurences", () => {
        assertHighlightProtonEqual(
            "I kept getting protons, protons and even more protons.",
            "[...] getting <b>proton</b>s, <b>proton</b>s and [...] more <b>proton</b>s."
        )
    })
})
