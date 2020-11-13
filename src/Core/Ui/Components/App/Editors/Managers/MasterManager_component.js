Vue.component("___idReference_", {
  mixins: [nbsComponentMixin],
  components : ___subcomponents_,
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;

    $('.helpPopup').popup();

    if(this.message != false){

      this.alertToast('error', 'ERROR', this.message);
    }

    $('.menu .item').tab();

    $('.ui.embed').embed();
  },
  methods: {

    doStateAction: function(action){
      var _self = this;
      console.log("doStateAction", action.toState);
      $("#" + action.toState + "_action_modal").modal({
        closable  : false,
        onDeny    : function(event){
          //window.alert('Wait not yet!');
          //return false;
        },
        onApprove : function(event) {
          console.log("onApprove", event);
          return _self.doSetNewState(action);
        }
      }).modal('show');
    },
    doSetNewState: function(action){
      console.log("doSetNewState", action.toState);
      var result      = false;
      
      var valid       = this.doStateActionValidateData(action);

      if(valid){

        var newState                    = action.toState;
        var newStateData                = {};

        _.each(this.$children , function (value, key, list){
            
            if(_.has(value.$attrs,"toStateAction")){
              
              if(value.$attrs.toStateAction == action.toState){
                console.log(action.toState);
                newStateData[value.$attrs.id]   = value.getFieldValue();
              }
            }
        });

        this.doSetNewStateState(newState);
        this.doSetNewStateData(newStateData);

        valid = this.doValidateData();

        if(valid){

          this.doSendData();
        }else{

          console.log("INVALID FORM");
        }
        
        result        = true;
      }else{

        result        = false;
      }

      return result;
    },
    doSetNewStateState: function (state){

      $("#field_objState").val(state);
    },
    doSetNewStateData: function (data){

      $("#stateData").val(JSON.stringify(data));
    },
    doStateActionValidateData: function(action){
      var _self   = this;

      var valid   = true;

      _.each(this.$children , function (value, key, list){

        if(valid){

          if(_.has(value.$attrs,"toStateAction")){

            if(value.$attrs.toStateAction == action.toState){

              valid = value.doValidateField();
            }
          }
        }
      });
      
      return valid;
    },
    getStateStyle: function(state){
      var _self = this;
      var result = "basic";

      _.each(this.fields.info.data.objState.options.states, function(value, key, list){

        if(value.id == state){

          result = value.style;
        }
      });

      return result;
    },
    getStateActions: function(){
      var _self = this;
      var result = [];

      _.each(this.fields.info.data.objState.options.states, function(value, key, list){
        console.log("STATE ACTIONS", value.id, "==" ,_self.objectData.objState, "VALUE =>", value);
        if(value.id == _self.objectData.objState){

          result = value.toStateActions;
        }
      });
      console.log("STATE ACTIONS RESULT", result);
      return result;
    },
    hasStates: function(){
      var _self = this;
      var result = false;
      
      if(_.has(this.objectData,"objState")){
        
        _.each(this.fields.info.data, function(value, key, list){
          
          if(key == "objState" && _.has(value.options, "states")){

            result = true;
          }
        });
      }
      
      return result;
      
    },
    getToStateActionFields: function(toState){
      return this.fields.statesActionFields[toState];
    },
    getGroupName: function (groupId) {

      return this.fieldsGroups[groupId];
    },
    getFieldFileOptions: function (options){

      return options.file;
    },
    getFieldValidationOptions: function (options){

      return options.validation;
    },
    getFieldOptions: function (options){

      var result = {};

      _.each(options, function(value, key, list){

        if(key != "validation" && key != "file"){

          result[key] = value;
        }
      });

      return result;
    },
    doValidateData: function(){
      var _self   = this;

      var valid  = true;

      _.each(this.$children , function (value, key, list){

        if(valid){

          if(!_.has(value.$attrs,"toStateAction")){

            console.log("CHILD", key, value.id, value.$attrs);
            valid = value.doValidateField();
          }
        }
      });

      return valid;
    },
    doSendData: function(){
      
      console.log("Send Data");

      this.$refs.selectorForm.submit();
    }
  },
  computed: {
  },
  template: "#___idReference_-template"
});
