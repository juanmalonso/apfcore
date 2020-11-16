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
    user: function(){
      return this.getScopeData("user", false);
    },
    items: function(){
      return this.getScopeData("items", []);
    }
  },
  template: "#___idReference_-template"
});
