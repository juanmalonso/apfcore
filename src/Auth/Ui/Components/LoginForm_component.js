Vue.component('___idReference_', {
    mixins: [nbsComponentMixin],
		data: function () {
			return ___jsdata_
		},
		mounted: function (){
			var _self = this;
			
			//$('.ui.sidebar').sidebar('setting', 'transition', 'overlay').sidebar('toggle');

			$( document ).ready(function() {
				
				_self.doMessage();
			});
		},
		methods: {
			doLogin: function(){
				this.message = "";

                console.log('Login FORM!!!');

                var valid = true;
				
                if(valid && $("#login").val() == ""){

                    valid = false;
                    this.message = "Ingrese un usuario válido";
                }

                if(valid && $("#password").val() == ""){

                    valid = false;
                    this.message = "Ingrese una contraseña";
                }

                if(valid){

                    $("#password").val(CryptoJS.SHA1($("#password").val()));
    
                    $('#form1').submit();
				}
				
				this.doMessage();
			},
			doMessage: function(){

				console.log(this.message);

				if(typeof this.message === 'string' && this.message.length > 2){

					this.alertToast('error', 'Login Error', this.message);
				}
			}
		},
		template: '#___idReference_-template'
});