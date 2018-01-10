export default function Debouncer () {
  let timeout = null

  /**
   * Do something after `delay' milliseconds, unless told otherwise inbetween.
   *
   * If you decide not to do anything after all, just don't pass a `what'.
   */
  this.after = function(delay, what) {
    if (timeout) clearTimeout(timeout)
    timeout = setTimeout(what || (function () {}), delay)
  }
}
