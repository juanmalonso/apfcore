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
      var self = this;

      $(this.$refs.realField).val(value);
      $(this.$refs.realField).flatpickr({
        enableTime: (self.getTypeOption("enableTime") != undefined) ? self.getTypeOption("enableTime") : true,
        dateFormat: (self.getTypeOption("dateFormat") != undefined) ? self.getTypeOption("dateFormat") : true,
        defaultDate: value
      });
    },
    getFieldValue: function () {

      return $(this.$refs.realField).val();
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
