Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;
  },
  methods: {
    doSearch: function(){
      keyword = $("#keyword").val();

      window.location = this.basepath + "v2lista/keyword:" + keyword;
    }
  },
  template: "#___tag_-template"
});
