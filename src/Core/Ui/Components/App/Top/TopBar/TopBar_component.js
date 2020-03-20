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
    },
    getReferenceNameValue: function(referenceName){

      return this.$root[this.serviceId].nameReferences[referenceName];
    },
    doComponentAction: function (actions, event){
      
      var _self = this;

      _.each(actions,function (value, key, list){

        switch (value.scope) {
          case "_local":
            _self[value.name](value.params);
            break;

          case "_service":
              _self.$root[_self.serviceId][value.name](value.params);
              break;

          case "_parent":
              _self.$root[_self.parentId][value.name](value.params);
              break;
        
          default:
            _self.$root[_self.getReferenceNameValue(value.scope)][value.name](value.params);
            break;
        }

      });
    }
  },
  template: "#___tag_-template"
});
