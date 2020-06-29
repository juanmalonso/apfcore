Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;
  },
  methods: {
    doSendData: function(){

      //TODO: VALIDACION
      console.log("SEND DATA");

      $("#reservaform").submit();
    }
  },
  template: "#___tag_-template"
});
