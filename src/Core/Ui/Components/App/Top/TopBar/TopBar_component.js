Vue.component("___idReference_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;
  },
  methods: {
    doRefereceTest:function(params){

      console.log("doRefereceTest", params);
    },
    doLocalTest:function(params){

      console.log("doLocalTest", params);
    }    
  },
  template: "#___idReference_-template"
});
