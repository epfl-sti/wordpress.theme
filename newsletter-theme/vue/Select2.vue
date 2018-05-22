<!--

select2 in vue!

Use like this:

Events:

- input: A new result has been selected

- search: The user is typing into the search box. Params are (query,
          pageno). Note that this is an advanced feature, as the event
          fires for every single search action (typing or scrolling to
          the end in the case of "infinite search"); consider using
          singlesearch instead

- singlesearch: The user is requesting a new search (by typing or
                scrolling to the end of an "infinite search") and no
                search is currently outstanding.

Known bugs:

- There is support for displaying custom messages (with the "message"
  slot), but it doesn't work reliably so it is turned off

More info:

- select2: https://select2.org/

- Embedding in Vue: https://vuejs.org/v2/examples/select2.html

 -->

<template>
<div>
  <!-- For whatever reason, *on Firefox only* the select2 jQuery plugin
       insists on computing a ridiculous value for the width like 27.5px
       See https://stackoverflow.com/a/34780303/435004 for a quantum of
       insight
       Thankfully, setting a width in the element style countermands
       this weird behaviour. -->
  <select ref="select" style="width: auto;">
    <slot name="results"
          :search="search"
          :status="status"></slot>
  </select>
</div>
</template>

<script>
import select2 from 'select2/dist/js/select2.full.js';
import _ from 'lodash';

/**
 * Do what select2 does at the initialization in the AJAX-less case
 *
 * Inspired from SelectAdapter.prototype.query
 */
function parseSelect (el) {
    var data = [];

    $(el).children().each(function () {
      // Filtering options and optgroups is done in parseItem

      // Original code: var option = self.item($option);
      var option = parseItem($(this));

      // We don't need the matcher business here.
      if (option !== null) {
        data.push(option);
      }
    });
    return data;
}

/**
 * Parse an <option> or <optgroup> like select2 does
 *
 * Inspired from SelectAdapter.prototype.item
 */
function parseItem ($option) {
    if (!$option.is('option') && !$option.is('optgroup')) {
      return;
    }

    var data = {};

    // Cache read (Utils.GetData) removed

    if ($option.is('option')) {
      data = {
        id: $option.val(),
        disabled: $option.prop('disabled'),
        selected: $option.prop('selected'),
        text:     $option.text(),
        contents: $option.children()
      };
    } else if ($option.is('optgroup')) {
      data = {
        text: $option.prop('label'),
        children: [],
        title: $option.prop('title'),
        contents: $option.children(':not(option)')
      };

      var $children = $option.children('option');
      var children = [];

      for (var c = 0; c < $children.length; c++) {
        var $child = $($children[c]);

        var child = parseItem($child);

        children.push(child);
      }

      data.children = children;
    }

    // Assuming this is not needed:
    // data = this._normalizeItem(data);
    data.element = $option[0];

    // Cache write (Utils.StoreData) removed

    return data;
}

const VueResultsAdapter = {
  hook (amd) {
    amd.define(
      'VueResultsAdapter',
      ['select2/utils', 'select2/results', 'select2/dropdown/infiniteScroll',
       'select2/dropdown/hidePlaceholder', 'select2/dropdown/selectOnClose'],
      function (Utils, Results, InfiniteScroll,
                HidePlaceholder, SelectOnClose) {

        function VueResultsDecorator (decorated, $element, options, dataAdapter) {
          decorated.call(this, $element, options, dataAdapter)
          // Make a pass to the _jqResults method:
          $element.data('vue-select2-results', this)
        }

        VueResultsDecorator.prototype.bind = function (decorated, container, $container) {
          decorated.call(this, container, $container)
          // We manage messages ourselves:
          container.listeners['results:message'] = []
        }

        VueResultsDecorator.prototype.template = function (decorated, result, container) {
          if (! result.contents) return
           $(container).append(result.contents.clone());
        }

        let adapter = Utils.Decorate(Results, VueResultsDecorator);
        adapter     = Utils.Decorate(adapter, InfiniteScroll);
        // adapter     = Utils.Decorate(adapter, HidePlaceholder);
        // adapter     = Utils.Decorate(adapter, SelectOnClose);
        return adapter;
      })
  }
}

export default {
  props: {
    value: String,
    theme: {
      type: String,
      default: "bootstrap4"
    },
    /* The search state. Select2 doesn't interpret this value in any
     * way, except to notice when it is not null (which means that
     * a search has started) and to pass it down to the slots.
     */
    search: Object,
    /* Setting this tells Select2 that the search is over and that it is time
     * to parse the "results" slot to find out the results. */
    status: {},
    /* Setting this to true enables "infinite scrolling". Instead of
     * resetting the search results, the next search will append to them. */
    more: {
      type: Boolean,
      default: false
    },
    /* The time to wait for the user to stop typing, before starting a
     * new search */
    delay: {
      type: Number,
      default: 250
    }
  },

  mounted: function () {
    if (! $.fn.select2) {
      // Load the jQuery plug-in. Inferred from reading the source:
      require("select2")(
        /* root = */ null,  // I have no idea what that parameter is for
        $)
      VueResultsAdapter.hook($.fn.select2.amd)
    }
    let vm = this
    $(this.$refs.select)
      // init select2
      .select2({
        theme: this.theme,
        data: this.options,
        resultsAdapter: $.fn.select2.amd.require("VueResultsAdapter"),
        ajax: {
          // Every time 
          delay: this.delay,
          url: (element, data) => data,
          transport(params, success) {
            vm._userIsSearching(params.data.term, params.data.page, success)
          }
        }
      })
      .val(this.value)
      .on('select2:select', function () {
        vm.$emit('input', this.value)
      })
  },

  methods: {
    _jqResults () {
      // Set by VueResultsDecorator, above
      if (! this.$refs.select) return
      return $(this.$refs.select).data('vue-select2-results')
    },
    _userIsSearching (term, page, success) {
      this._search = {
        success: _.once(success)
      }

      let hadSearch = !! this.search

      // Listeners to the "search" event always get told that the user
      // is typing (or scrolling). It is up to them to manage
      // cancellations, concurrent searches etc.
      this.$emit("search", term, page)

      if (! (hadSearch && ! this.status)) {
        // Listeners to "singlesearch" are excused from some of the
        // bookkeeping, but cannot start another search as long as the
        // previous one is in progress
        this.$emit("singlesearch", term, page)
      }

      if (! hadSearch) {
        this.$nextTick(() => {
          if (this.search && ! this.status) {
            console.log("Search started")
            
            let results = this._jqResults()
            if (results) results.showLoading()
          } else if (this.search && this.status) {
            console.log("Parent has completed search in one game turn")
            delete this._search
          } else {  // Still ! this.search
            console.log("Parent has declined to search for ", term)
            delete this._search
          }
        })
      }
    }
  },

  watch: {
    /**
     * When parent component sets search, and sets status to null,
     * it means that an asynchronous search has started. If we knew
     * how, we would update the select2 built-in status message here.
     */
    search (newVal, oldVal) {
    },
    /**
     * When parent component transitions status from null to non-null,
     * with search also non-null, it means that a search is over. It
     * is then time to pass on both search and status to the scoped slot,
     * so as to harvest the search results on $nextTick.
     */
    status (newVal, oldVal) {
      if (! (newVal && !oldVal)) {
        // TODO: implement more non-happy-path cases here?
        return;
      }
      if (! this._search) {
        console.log("I don't remember starting this search?");
        return;
      }
      let vm = this,
          success = this._search.success
      vm.$nextTick(() => {
        success({results: parseSelect(vm.$refs.select),
                 pagination: {more: vm.more}})
      })
      delete this._search
    },

    /**
     * When parent component updates value, let the select2 jQuery code know
     */
    value (value) {
      $(this.$refs.select).val(value)
    }
  },
  destroyed: function () {
    $(this.$refs.select).off().select2('destroy')
  }
}
</script>

<style lang="scss">
@import "~select2/dist/css/select2";
@import "~select2-bootstrap4-theme/dist/select2-bootstrap4";
</style>
