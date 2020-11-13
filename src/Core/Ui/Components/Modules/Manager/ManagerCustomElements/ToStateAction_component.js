Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;

    self.registerComponent();
  },
  methods: {
    isFieldRender: function (fieldData) {
      var result = false;

      _.each(this.$attrs.renderedFields, function (value, key) {
        
        if(!result){
          
          if(value == fieldData.id){

            result = true;
          }
        }
      });

      return result;
    },
    doStateAction(){
      
      if(this.stateActionData.type == "toState"){

        console.log("TO STATE ACTION", this);
      }
      
      if(this.stateActionData.type == "actions"){

        console.log("TO STATE ACTION", this);

        this.doComponentAction(this.stateActionData.actions, {}, null);
      }
    }
  },
  computed: {
    stateActionData:function(){
      return this.getScopeData("stateActionData",this.$attrs.stateActionData);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps",this.$attrs.urlMaps);
    },
  },
  template: "#___idReference_-template"
});