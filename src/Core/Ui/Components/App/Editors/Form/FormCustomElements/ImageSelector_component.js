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

    
    
    //FIELD
    /*
    if(this.$attrs.editAs == "FIELD"){

      $('#field_' + this.$attrs.id).val(value);
      $('#field_' + this.$attrs.id).dropdown('set selected',value);

    }else if(this.$attrs.editAs == "LIST"){

      if(_.isArray(value)){

        _.each(value, function(value, key, list){

          $("input[value='" + value + "']").parent().checkbox('check');
        });
      }
    }*/

    $('#sidebar_' + this.$attrs.id).sidebar('setting', 'transition', 'overlay');
    $('#sidebar_' + this.$attrs.id).sidebar('setting', 'closable', false);
  },
  methods: {
    getFieldValue: function () {

      var result = null;
      /*
      if(this.$attrs.editAs == "FIELD"){

        result = $('#field_' + this.$attrs.id).val();
  
      }else if(this.$attrs.editAs == "LIST"){
        result = [];

          $("input[name='" + this.$attrs.id + "']").each(function (){

            if($(this).parent().checkbox("is checked")){
  
              result.push($(this).val());
            }
          });
        
      }
      console.log(result);
      */
      return result;
    },
    doOpenImageSelectorSlide: function(){

      $('#sidebar_' + this.$attrs.id).sidebar('show');
    },
    doCloseImageSelectorSlide: function(){

      $('#sidebar_' + this.$attrs.id).sidebar('hide');
    },
    doBrowseFile: function(){

      $("#upload_buttom_" + this.$attrs.id).addClass("disabled");

      $('#file_' + this.$attrs.id).click();
    },
    onSelectedFile: function (event){
      var _self   = this;
      var reader  = new FileReader();
      var file    = event.target.files[0];
      reader.onload = function(){

        if(file.name.substring(file.name.length - 4, file.name.length) == 'jpeg'){

          _self.uploadImageName        = file.name.substring(0, file.name.length - 5);
          _self.uploadImageExtension   = file.name.substring(file.name.length - 4, file.name.length);
        }else{

          _self.uploadImageName        = file.name.substring(0, file.name.length - 4);
          _self.uploadImageExtension   = file.name.substring(file.name.length - 3, file.name.length);
        }

        _self.uploadImageTypemime      = file.type;
        _self.uploadImageSize          = file.size;
        _self.uploadImageData          = reader.result.replace("data:" + file.type + ";base64,", "");

        $("#upload_buttom_" + _self.$attrs.id).removeClass("disabled");
      };

      reader.readAsDataURL(file);
    },
    doUploadImage: function (event){

			console.log("doUploadImage", event);

			var serviceOptions = {
				data: {
          name:this.uploadImageName,
          description:this.uploadImageDescription,
          typemime:this.uploadImageTypemime,
          extension:this.uploadImageExtension,
          size:this.uploadImageSize,
          image:this.uploadImageData
				},
				successcbk:this.onUploadImageSuccess,
				errorcbk:this.onUploadImageError,
        loading:true,
        emitter:$(event.target)
			}

			this.loadService("upload", serviceOptions);
		},
		onUploadImageSuccess: function (response, data){

			console.log("UPLOAD SUCCESS", response, data);
    },
    onUploadImageError: function (response, message){

			console.log("UPLOAD ERROR", response, message);
		},    
    doValidateField: function (){

      return this.validateDefinitions();
    },
    isMultiple : function(){
      var result = false;

      if(this.$attrs.options.multiple != undefined){

        result = this.$attrs.options.multiple; 
      }

      return result;
    }
  },
  computed: {
  },
  template: "#___tag_-template"
});
