Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;
  },
  methods: {
  },
  computed:{
    title: function(){
      return this.getScopeData("title", "none");
    },
    actions: function(){
      return this.getScopeData("actions");
    }
  },
  template: "#___idReference_-template"
});
