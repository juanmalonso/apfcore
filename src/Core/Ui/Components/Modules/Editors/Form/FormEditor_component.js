Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    
    $('.menu .item').tab();
  },
  methods: {
    generateReference: function(){
      var result  = this.referenceName;

      if (_.has(this.$attrs, 'id')) {

        result    = this.$attrs.id;
      }
      
      return result;
    },
    onAttrWatchers: function(){

      this.modLoadDataService(this.generateDataServiceOptions({}),false);
    },
    onCustomModLoadDataServiceStart: function(options){

      this.setScopeData("fields",[]);
    },
    doSendData: function(params, event){

      if(this.validateFormData()){
        
        var dataServiceData = {
          "data":this.recopileFormData()
        };
        
        var overwriteDataServiceOptions           = {};
        overwriteDataServiceOptions.data          = dataServiceData;
        //overwriteDataServiceOptions.successcbk    = this.onSendDataSuccess;
        //overwriteDataServiceOptions.errorcbk      = this.onSendDataError;
        
        console.log("doSendData - DATA", dataServiceData);
        console.log("doSendData - DATA SERVICE", this.generateDataServiceOptions(overwriteDataServiceOptions));

        this.modLoadDataService(this.generateDataServiceOptions(overwriteDataServiceOptions), false);
      }
    },
    onSendDataSuccess: function (response, data) {

      console.log("+++ onSendDataSuccess +++", response, data);

      //this.setMultipleScopeData(data);
    },
    onSendDataError: function (response, message) {

        console.log("onSendDataError", response, message);
    },
    validateFormData: function (){
      
      var self              = this;
      var valid             = true;
      
      _.each(this.getFieldsReferences(), function (value, key, list){
        
        if(valid && self.hasByReferenceName(value)){

          var fieldValidations  = {};

          if(_.has(self.validations, key)){

            fieldValidations    = self.validations[key]; 
          }

          valid = (self.getByReferenceName(value)).doValidateField(fieldValidations);
        }
      });
      
      //EXPRESSION FORM
      
      if(valid && _.has(self.validations, 'expression')){
       
        var expressionValidation    = self.validations.expression;
        
        var scope                   = self.recopileFormData();
        scope.fields                = self.fields;
        scope.model                 = self.model;
        scope.isPhoneDevice         = self.isPhoneDevice;
        scope.referenceName         = self.referenceName;
        
        var expressionResult        = math.evaluate(expressionValidation.expression, scope);
        
        if(_.isBoolean(expressionResult)){

          if(!expressionResult){

            valid                   = false;

            if (_.has(expressionValidation, 'message')) {

              self.alertToast('error', 'ERROR', expressionValidation.message);
              
            } else {

              self.alertToast('error', 'ERROR', "Expression " + expressionValidation.expression + " Unsuccessfully in Form");
            }
          }
        }
      }
      
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
    isCustomField: function(field){

      return (_.indexOf(this.customFields, field) != -1) ? true : false;
    },
    getFieldsReferences: function(){
      var self = this;
      var result = {};

      _.each(this.fields, function (value, key) {
        
        result[value.id]         = self.referenceName + "_form_" + value.id + "_field";
      });
      
      return result;
    },
    getGroupFields: function (group){
      var self    = this;
      var result  = {};

      _.each(self.fields, function (value, key) {

        if(value.group == group){

          result[key] = value;
        }
      });

      return result;
    },
    getFieldsGroups: function(){
      var self    = this;
      var result  = {};

      _.each(self.fields, function (value, key) {

        if(!_.has(result, value.group)){

          result[value.group] = {"key":value.group, "label":self.fieldsGroups[value.group]};
        }
      });

      console.log("GET FIELDS GROUPS", result);
      return result;
    },
    setTabs: function(id){
      
      $(".formtab").tab();
    },
  },
  computed: {
    model:function(){
      return this.getScopeData("model");
    },
    actions:function(){
      return this.getScopeData("actions");
    },
    dataActions:function(){
      return this.getScopeData("dataActions");
    },
    options:function(){
      return this.getScopeData("options");
    },
    fields:function(){
      return this.getScopeData("fields");
    },
    fieldsGroups:function(){
      return this.getScopeData("fieldsGroups");
    },
    validations:function(){
      return this.getScopeData("validations", {});
    },
    objects:function(){
      return this.getScopeData("objects");
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps");
    }
  },
  template: "#___idReference_-template"
});
