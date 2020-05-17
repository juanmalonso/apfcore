Vue.component("___tag_", {
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
  template: "#___tag_-template"
});
