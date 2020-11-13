Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;
    
  },
  methods: {
    initField: function(){

      //
    },
    setFieldValue: function (value) {
      var self        = this;
      var checkValue  = "uncheck";

      $(this.$refs.realField).val(value);

      if(this.field.type == "boolean"){
        // true || false

        if(value == "true" || value == true){
  
          checkValue = "check";
        }else if(value == "false" || value == false){
  
          checkValue = "uncheck";
        }

      }else if(this.field.type == "boolean01"){
        // 1 || 0

        if(value == "1" || value == 1){
          
          checkValue = "check";
        }else if(value == "0" || value == 0){
          
          checkValue = "uncheck";
        }
      }

      $(this.$refs.checkBox).checkbox(checkValue);
      $(this.$refs.checkBox).checkbox({
        onChecked: function() {
  
          self.doChecked();
        },
        onUnchecked: function() {
  
          self.doUnchecked();
        }
      });

    },
    getFieldValue: function () {

      return $(this.$refs.realField).val();
    },
    doUnchecked: function (){

      if(this.field.type == "boolean"){
        
        $(this.$refs.realField).val("false");
      }else if(this.field.type == "boolean01"){

        $(this.$refs.realField).val("0");
      }
    },
    doChecked: function (){

      if(this.$attrs.valueAs == "boolean"){

        $(this.$refs.realField).val("true");
      }else if(this.$attrs.valueAs == "binary"){

        $(this.$refs.realField).val("1");
      }
    },
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
