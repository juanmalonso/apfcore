Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;
    
  },
  methods: {
    setFieldValue: function (value) {

      $(this.$refs.realField).val(value);
    },
    getFieldValue: function () {

      return $(this.$refs.realField).val();
    },
    getFieldRepeatValue: function () {

      return $(this.$refs.realFieldRepeat).val();
    },
    customValidation: function(value, valid){

      this.doHashFieldValue();

      if(valid && (this.getFieldValue() != this.getFieldRepeatValue())){

        this.doMessageToast("Las contraseñas deben ser iguales");

        valid = false;
      }

      return valid;
    },
    doHashFieldValue: function (){
      
      $(this.$refs.realField).val(CryptoJS.SHA1($(this.$refs.realField).val()));
      $(this.$refs.realFieldRepeat).val(CryptoJS.SHA1($(this.$refs.realFieldRepeat).val()));
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
