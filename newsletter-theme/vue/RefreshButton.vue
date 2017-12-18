<template>
  <button @click.prevent="submitThenRefresh">Refresh</button>
</template>

<script>
import WPajax from "../inc/ajax.js"

export default {
    props: {
        state: { type: Object, required: true }
    },
    data: () => ({}),
    methods: {
        submitThenRefresh () {
            let self = this
            console.log(self.state)
            WPajax({
                  action: "epfl_sti_newsletter_draft_save",
                  state: self.state
            })
            .then(() => {
                // https://stackoverflow.com/a/4249946/435004
                $( 'iframe' ).attr( 'src', function ( i, val ) { return val; });
            })
            .catch((e) => {
                self.$emit("error", e)
            })
        }
    }
}
</script>
