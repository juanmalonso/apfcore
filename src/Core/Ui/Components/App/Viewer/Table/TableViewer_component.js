Vue.component("___tag_", {
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
  },
  methods: {
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

          valid = value.doValidateField();
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
  template: "#___tag_-template"
});
