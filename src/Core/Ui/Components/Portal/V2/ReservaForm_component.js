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

      $("#reserva_nombres").removeClass("error");
      $("#reserva_apellidos").removeClass("error");
      $("#reserva_email").removeClass("error");
      $("#reserva_telefono").removeClass("error");

      //NOMBRES
      if(valid && $("#reserva_nombres").val() == ""){

          field   = "reserva_nombres";
          message = "Debe ingrasar su Nombre";
          valid   = false;
      }

      //APELLIDOS
      if(valid && $("#reserva_apellidos").val() == ""){

        field   = "reserva_apellidos";
        message = "Debe ingrasar su Apellido";
        valid   = false;
      }

      //EMAIL
      if(valid && $("#reserva_email").val() == ""){

        field   = "reserva_email";
        message = "Debe ingrasar un eMail";
        valid   = false;
      }

      //TELEFONO
      if(valid && $("#reserva_telefono").val() == ""){

        field   = "reserva_telefono";
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

        $("#reserva_form").submit();
      }
    }
  },
  template: "#___tag_-template"
});
