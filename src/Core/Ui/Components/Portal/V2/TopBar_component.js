Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;

    $(document).ready(function (){

      console.log("MOUSEFLOW");

      //MOUSEFLOW
      window._mfq = window._mfq || [];

      (function() {
        var mf = document.createElement("script");
        mf.type = "text/javascript";
        mf.defer = true;
        mf.src = "//cdn.mouseflow.com/projects/389ac532-b0b7-46e1-983d-d83890dbad7c.js";
        document.getElementsByTagName("head")[0].appendChild(mf);
      })();
    });

  },
  methods: {},
  template: "#___tag_-template"
});
