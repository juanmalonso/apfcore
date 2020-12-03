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
    doSendStateAction: function(){

      if(this.validateFormData()){
        
        var formData      = this.recopileFormData();

        var dataServiceData = {
          "model":this.model.id,
          "id":this.dataRow._id,
          "state":this.stateActionData.toState,
          "data":formData
        };
        
        var overwriteDataServiceOptions           = {};
        overwriteDataServiceOptions.serviceName   = "to-state";
        overwriteDataServiceOptions.data          = dataServiceData;
        overwriteDataServiceOptions.successcbk    = this.onSendStateSuccess;
        overwriteDataServiceOptions.errorcbk      = this.onSendStateError;
        
        console.log("doSendStateAction - DATA", dataServiceData);
        console.log("doSendStateAction - DATA SERVICE", this.generateDataServiceOptions(overwriteDataServiceOptions));

        this.modLoadDataService(this.generateDataServiceOptions(overwriteDataServiceOptions), false);
      }
    },
    onSendStateSuccess: function (response, data) {
      
      console.log("onSendStateSuccess", response, data);

      this.doCloseStateSlider({"slider":this.stateActionData.toState});

      this.doParent("doReloadData", {}, {}, null);
    },
    onSendStateError: function (response, message) {

      console.log("onSendStateError", response, message);

      this.doCloseStateSlider({"slider":this.stateActionData.toState});

      this.doParent("doReloadData", {}, {}, null);
    },
    validateFormData: function (){
      
      var self              = this;
      var valid             = true;
      
      _.each(this.getFieldsReferences(), function (value, key, list){
        
        if(valid && self.hasByReferenceName(value)){
          
          valid = (self.getByReferenceName(value)).doValidateField();
        }
      });

      return valid;
    },
    recopileFormData: function(){

      var self              = this;
      var result            = {};

      _.each(this.getFieldsReferences(), function (value, key, list){

        if(self.hasByReferenceName(value)){
        
          result[key] = (self.getByReferenceName(value)).getFieldValue();
        }
      });

      return result;
    },
    getFieldsReferences: function(){
      var self = this;
      var result = {};

      _.each(this.stateActionData.toStateInputData, function (value, key) {
        
        result[key]         = self.referenceName + "_form_" + key + "_field";
      });
      
      return result;
    },
    doStateAction(){
      
      if(this.stateActionData.type == "toState"){

        console.log("TO STATE ACTION", this);

        this.doOpenStateSlider({"slider":this.stateActionData.toState});
      }
      
      if(this.stateActionData.type == "actions"){

        console.log("TO STATE ACTION", this);

        this.doComponentAction(this.stateActionData.actions, {}, null);
      }
    },
    doOpenStateSlider: function (params){
      var self              = this;

      this.maxzindex = this.maxzindex + 100;

      $("#" + params.slider + "_sidebar").css('z-index', this.maxzindex);

      $("#" + params.slider + "_sidebar").sidebar('setting', 'transition', 'overlay');
      console.log($("#" + params.slider + "_sidebar"));

      $("#" + params.slider + "_sidebar").sidebar('setting', 'closable', false);
      $("#" + params.slider + "_sidebar").sidebar('setting', 'exclusive', false);
      $("#" + params.slider + "_sidebar").sidebar('show');
    },
    doCloseStateSlider: function (params){
      var tmp_opensliders   = [];

      $("#" + params.slider + "_sidebar").sidebar('hide');
    },
  },
  computed: {
    dataRow:function(){
      return this.getScopeData("datarow",this.$attrs.dataRow);
    },
    model:function(){
      return this.getScopeData("model",this.$attrs.model);
    },
    stateActionData:function(){
      return this.getScopeData("stateActionData",this.$attrs.stateActionData);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps",this.$attrs.urlMaps);
    },
  },
  template: "#___idReference_-template"
});