Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;

    $('.ui.dropdown').dropdown({
      placeholder: "auto"
    });

    $('#togglenacionales').checkbox();
  },
  methods: {
    doSearch: function(){
      url = this.basepath + "v2lista/";

      //KEYWORD
      keyword = $("#keyword").val();
      if(keyword != ""){

        url += "keyword:" + keyword + "/";
      }

      //MES
      mes = $("#mes").val();
      if(mes != "" && mes != "all"){

        url += "mes:" + mes + "/";
      }

      //NACIONALES?
      nacionales = $("#togglenacionales").checkbox("is checked");
      if(nacionales){

        url += "paises:PY";
      }

      window.location = url;
    }
  },
  template: "#___tag_-template"
});
