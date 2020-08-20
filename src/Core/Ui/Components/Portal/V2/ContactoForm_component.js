Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;
  },
  methods: {
    validateFormData: function(){

      var valid   = true;
      var message = "";
      var field   = "";

      $("#contacto_nombre").removeClass("error");
      $("#contacto_email").removeClass("error");
      $("#contacto_telefono").removeClass("error");

      //NOMBRES
      if(valid && $("#contacto_nombre").val() == ""){

          field   = "contacto_nombre";
          message = "Debe ingrasar su Nombre y Apellido";
          valid   = false;
      }

      //EMAIL
      if(valid && $("#contacto_email").val() == ""){

        field   = "contacto_email";
        message = "Debe ingrasar un eMail";
        valid   = false;
      }

      //TELEFONO
      if(valid && $("#contacto_telefono").val() == ""){

        field   = "contacto_telefono";
        message = "Debe ingrasar un Telefono";
        valid   = false;
      }

      //CAPCHA
      
      var capchaResponse = grecaptcha.getResponse();
      if(valid && capchaResponse.length == 0){

          field   = "";
          message = "Validar el Capcha es Requedrido!";
          valid   = false;
      }

      if(!valid){

          if(field != ""){

              $("#" + field).addClass("error");
          }

          this.alertToast('error', 'ERROR', message);
      }

      return valid;
    },
    doSendData: function(){
      
      var valid = this.validateFormData();
      console.log("VALID FORM", valid);
      if(valid){

        $("#contacto_form").submit();
      }
    }
  },
  template: "#___tag_-template"
});
