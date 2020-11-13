Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self       = this;
    var value       = this.$attrs.default;
    var checkValue  = "uncheck";
    
    if(_.has(this.$attrs.data, this.$attrs.id)){
      
      value = _self.$attrs.data[this.$attrs.id];
    }
    
    $('#field_' + this.$attrs.id).val(value);

    if(this.$attrs.valueAs == "boolean"){

      if(value == "true" || value == true){

        checkValue = "check";
      }else if(value == "false" || value == false){

        checkValue = "uncheck";
      }
    }else if(this.$attrs.valueAs == "binary"){
      
      if(value == "1" || value == 1){
        
        checkValue = "check";
      }else if(value == "0" || value == 0){
        
        checkValue = "uncheck";
      }
    }

    $("#" + this.$attrs.id + "_checkbox").checkbox(checkValue);
    $("#" + this.$attrs.id + "_checkbox").checkbox({
      onChecked: function() {

        _self.doChecked();
      },
      onUnchecked: function() {

        _self.doUnchecked();
      }
    });
  },
  methods: {
    doUnchecked: function (){

      if(this.$attrs.valueAs == "boolean"){

        $('#field_' + this.$attrs.id).val("false");
      }else if(this.$attrs.valueAs == "binary"){

        $('#field_' + this.$attrs.id).val("0");
      }

      console.log($('#field_' + this.$attrs.id).val());
    },
    doChecked: function (){

      if(this.$attrs.valueAs == "boolean"){

        $('#field_' + this.$attrs.id).val("true");
      }else if(this.$attrs.valueAs == "binary"){

        $('#field_' + this.$attrs.id).val("1");
      }

      console.log($('#field_' + this.$attrs.id).val());
    },
    getFieldValue: function () {

      return $('#field_' + this.$attrs.id).val();
    },
    doValidateField: function (){

      return this.validateDefinitions();
    }
  },
  computed: {
  },
  template: "#___idReference_-template"
});
