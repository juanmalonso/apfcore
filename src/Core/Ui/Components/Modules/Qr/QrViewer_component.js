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
  },
  computed: {
    qrEncodedData: function(){
      return this.getScopeData("qrEncodedData");
    },
    qrData: function(){
      return this.getScopeData("qrData");
    }
  },
  template: "#___idReference_-template"
});
