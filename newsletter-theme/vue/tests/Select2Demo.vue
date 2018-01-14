<!-- A demo for ../Select2.vue

     This is the same example as https://select2.org/data-sources/ajax
     with the same API case (the GitHub search-as-you-type), CSS styles
     and per-result HTML.
 -->

<template>
  <select2 v-model="picked" @search="doSearch" :more="more">
    <p slot="message" style="color: blue;">{{ searchMessage }}</p>
    <template slot="results" slot-scope="snitch">
      <option v-for="repo in items" :value="repo.id">
        <div class="select2-result-repository clearfix">
          <div class="select2-result-repository__avatar"><img :src="repo.owner.avatar_url" /></div>
          <div class="select2-result-repository__meta">
            <div class="select2-result-repository__title">{{ repo.full_name }}</div>
            <div class="select2-result-repository__description" v-if="repo.description">{{ repo.description }}</div>
            <div class="select2-result-repository__statistics">
              <div class="select2-result-repository__forks"><i class="fa fa-flash"></i>{{ repo.forks_count }} Forks</div>
              <div class="select2-result-repository__stargazers"><i class="fa fa-star"></i>{{ repo.stargazers_count }} Stars</div>
              <div class="select2-result-repository__watchers"><i class="fa fa-eye"></i> {{ repo.watchers_count }} Watchers</div>
            </div>
          </div>
        </div>
      </option>
      {{ snitch.done() }}
    </template>
  </select2>
</template>

<script>
import select2 from "../Select2.vue"
import mockGitAPIData from "./mockGitAPIData.json"

export default {
  props: {
    offline: Boolean
  },
  data: () => ({
    searchMessage: null,
    more: null,
    picked: null,
    items: [],
    loading: false
  }),

  components: { select2 },

  watch: {
    picked (newVal) {
      console.log(newVal)
    }
  },

  methods: {
    doSearch(term, page, that) {
      let vm = this
      if (vm.loading) return
      if (!term) return
      if (term.length < 3) {
        vm.searchMessage = "Please type more."
        that.cancel()
        return
      }

      if (! page) page = 1
      if (vm.offline) {
        setTimeout(function() {
          vm.items = mockGitAPIData.items
          vm.loading = false
        }, 4000)
      } else {
        $.ajax({
          url: "https://api.github.com/search/repositories?" +
            $.param({q: term, page: page || 1}),
            dataType: "json"
        }).done(function(results) {
          vm.loading = false
          vm.items = results.items
          vm.more  = page * 30 < results.total_count
        }).fail(function(error) {
          vm.loading = false
          that.fail(error)
        })
      }
    }
  }
}
</script>
<style scoped>
.select2-result-repository {
 padding-top:4px;
 padding-bottom:3px
}
.select2-result-repository__avatar {
 float:left;
 width:60px;
 margin-right:10px
}
.select2-result-repository__avatar img {
 width:100%;
 height:auto;
 border-radius:2px
}
.select2-result-repository__meta {
 margin-left:70px
}
.select2-result-repository__title {
 color:black;
 font-weight:700;
 word-wrap:break-word;
 line-height:1.1;
 margin-bottom:4px
}
.select2-result-repository__forks,.select2-result-repository__stargazers {
 margin-right:1em
}
.select2-result-repository__forks,.select2-result-repository__stargazers,.select2-result-repository__watchers {
 display:inline-block;
 color:#aaa;
 font-size:11px
}
.select2-result-repository__description {
 font-size:13px;
 color:#777;
 margin-top:4px
}
.select2-results__option--highlighted .select2-result-repository__title {
 color:white
}
.select2-results__option--highlighted .select2-result-repository__forks,.select2-results__option--highlighted .select2-result-repository__stargazers,.select2-results__option--highlighted .select2-result-repository__description,.select2-results__option--highlighted .select2-result-repository__watchers {
 color:#c6dcef
}
</style>
