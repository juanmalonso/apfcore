Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;

    if(!_.isNull(this.keyword)){

      $("#keyword").val(this.keyword);
    }

    $('#mes').dropdown({
      placeholder: "auto"
    });

    if(!_.isNull(this.mes)){

      $('#mes').dropdown("set selected", this.mes);
    }

    $('#togglenacionales').checkbox();

    if(!_.isNull(this.paises) && this.paises == "PY"){

      $("#togglenacionales").checkbox("check");
    }

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
