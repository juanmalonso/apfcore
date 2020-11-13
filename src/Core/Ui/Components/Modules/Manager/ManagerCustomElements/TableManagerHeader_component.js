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
  computed: {
    model:function(){
      return this.getScopeData("model",this.$attrs.model);
    },
    fields:function(){
      return this.getScopeData("fields",this.$attrs.fields);
    },
    dataRow:function(){
      return this.getScopeData("dataRow",this.$attrs.dataRow);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps",this.$attrs.urlMaps);
    },
  },
  template: "#___idReference_-template"
});