Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    var value = this.$attrs.default;
    
    if(_.has(this.$attrs.data, this.$attrs.id)){
      
      value = _self.$attrs.data[this.$attrs.id];
    }
    
    $('#field_' + this.$attrs.id).val(value);
  },
  methods: {
    getFieldValue: function () {

      return $('#field_' + this.$attrs.id).val();
    },
    getFieldRepeatValue: function () {

      return $('#field_repeat_' + this.$attrs.id).val();
    },
    doHashFieldValue: function (){

      $('#field_' + this.$attrs.id).val(CryptoJS.SHA1($('#field_' + this.$attrs.id).val()));
      $('#field_repeat_' + this.$attrs.id).val(CryptoJS.SHA1($('#field_repeat_' + this.$attrs.id).val()));
    },
    doValidateField: function (){

      var valid = this.validateDefinitions();

      if(valid && (this.getFieldValue() != this.getFieldRepeatValue())){

        this.doMessageToast("Las contraseñas deben ser iguales");

        valid = false;
      }

      if(valid){

        this.doHashFieldValue();
      }

      return valid;
    }
  },
  computed: {

  },
  template: "#___idReference_-template"
});
