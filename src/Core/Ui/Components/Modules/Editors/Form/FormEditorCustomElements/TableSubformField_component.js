Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;

    
  },
  methods: {
    getFieldData: function(fieldId){
      var self = this;
      var result = false;

      _.each(self.field.typeOptions.groups, function (valueg, keyg, list) {
        
        _.each(valueg.fields, function (valuef, keyf, list) {

          if(result == false && valuef.id == fieldId){

            result = valuef;
          }
        });
      });

      return result;
    },
    setFieldStyle: function (){
      var self = this;

      _.each(self.field.typeOptions.groups, function (valueg, keyg, list) {
        
        _.each(valueg.fields, function (valuef, keyf, list) {

          //HIDDEN
          if(_.has(valuef, "hidden")){

            if(valuef.hidden == true){

              self.doHideField({"fieldId":valuef.id});
            }
          }
        });
      });
    },
    doShowField: function(params){

      $('#' + this.field.id + '_' + params.fieldId + '_field').show();
    },
    doHideField: function(params){

      $('#' + this.field.id + '_' + params.fieldId + '_field').hide();
    },
    doFlushField: function(params){
      //TODO ; VER SI ES OTRO TYPE de field
      $('#' + this.field.id + '_' + params.fieldId + '_field').val("");
    },
    setCheckbox: function(fieldId){
      var self = this;
      var checkOptions = {};
      var fieldData   = this.getFieldData(fieldId);

      if(_.has(fieldData, "onChange")){

        if(fieldData.onChange.type == "actions"){

          checkOptions.onChange = function() {

            self.doComponentAction(fieldData.onChange.actions, {}, null);
          };
        }
      }

      if(_.has(fieldData, "onChecked")){

        if(fieldData.onChecked.type == "actions"){

          checkOptions.onChecked = function() {

            self.doComponentAction(fieldData.onChecked.actions, {}, null);
          };
        }
      }

      if(_.has(fieldData, "onUnchecked")){

        if(fieldData.onUnchecked.type == "actions"){

          checkOptions.onUnchecked = function() {

            self.doComponentAction(fieldData.onUnchecked.actions, {}, null);
          };
        }
      }

      $("#" + self.field.id + '_' + fieldId + '_checkbox').checkbox(checkOptions);
    },
    setRadio: function(fieldId, radioIndex){
      var self = this;

      $("#" + self.field.id + '_' + fieldId + '_radio_' + radioIndex).checkbox();
    },
    setFieldValue: function (value) {
      var self = this;

      $("#" + self.field.id + "_field").val(JSON.stringify(value, null, 2));
    },
    getFieldValue: function () {
      var self            = this;
      var finalFieldValue = {};

      console.log("SUB FIELD GETFIELDVALUE");

      _.each(self.field.typeOptions.groups, function (valueg, keyg, list) {
        
        _.each(valueg.fields, function (valuef, keyf, list) {

          switch (valuef.type) {
            case "text":
              
              value = $("#" + self.field.id + "_" + valuef.id + "_field").val();

              if(value != ""){

                finalFieldValue[valuef.id] = value;
              }

              break;
            case "textarea":
              
              value = $("#" + self.field.id + "_" + valuef.id + "_field").val();

              if(value != ""){

                finalFieldValue[valuef.id] = value;
              }

              break;
            case "boolean":

              if($("#" + self.field.id + '_' + valuef.id + '_checkbox').checkbox("is checked")){

                finalFieldValue[valuef.id] = true;
              }else{

                finalFieldValue[valuef.id] = false;
              }

              break;

            default:
              break;
          }
        });
      });

      this.setFieldValue(finalFieldValue);

      console.log("FINAL FIELD VALUE", finalFieldValue);

      return finalFieldValue;
    },
    customValidation: function(value){
      
      console.log("CUSTOM VALIDATION");

      return true;
    }
  },
  computed: {
    forForm:function(){
      return this.getScopeData("forForm", this.$attrs.forForm);
    },
    field:function(){
      return this.getScopeData("field", this.$attrs.field);
    },
    fieldIndex:function(){
      return this.getScopeData("fieldIndex", this.$attrs.fieldIndex);
    },
    data:function(){
      return this.getScopeData("data", this.$attrs.data);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps", this.$attrs.urlMaps);
    }
  },
  template: "#___idReference_-template"
});
