Vue.component("___tag_", {
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
    doValidateField: function (){

      return this.validateDefinitions();
    }
  },
  computed: {
  },
  template: "#___tag_-template"
});
