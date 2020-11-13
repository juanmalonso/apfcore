Vue.component("___idReference_", {
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
    
    if(_.isArray(value)){
      
      _.each(value, function(value, key, list){

        var propertyName = "_" + key;
        
        _self.$set(_self.images, propertyName, value);
      });
      
    }else{

      if(value != ""){

        this.$set(this.images, "_0", value);
      }
    }
    
    $('#field_' + this.$attrs.id).val(_.values(this.images));
    $('#field_' + this.$attrs.id).hide();

    $('#sidebar_' + this.$attrs.id).sidebar('setting', 'transition', 'overlay');
    $('#sidebar_' + this.$attrs.id).sidebar('setting', 'closable', false);

    $('.imageSelectorTab').on('click',function (event){

      console.log();

      if($(this).attr("data-tab") == 'gallery'){

        _self.doGalleryPage();
      }
    });
  },
  methods: {
    getFieldValue: function () {

      var result = null;
      
      result = $('#field_' + this.$attrs.id).val();
      console.log(result);
      
      return result;
    },
    doOpenImageSelectorSlide: function(index){
      
      console.log("doOpenImageSelectorSlide", index);
      
      $('#sidebar_' + this.$attrs.id).sidebar('show');

      this.imageSelectedIndex = index;

      this.doImageUnselected();

      if(_.has(this.images, index)){

        this.doImageSelected(this.images[index]);
      }

      this.doDisabledUploadButton();
      this.doDisabledAceptarButton();

      this.uploadImageName        = "";
      this.uploadImageDescription = "";
      this.uploadImageExtension   = "";
      this.uploadImageTypemime    = "";
      this.uploadImageSize        = "";
    },
    doCloseImageSelectorSlide: function(){

      $('#sidebar_' + this.$attrs.id).sidebar('hide');
    },
    doBrowseFile: function(){

      $("#upload_buttom_" + this.$attrs.id).addClass("disabled");

      $('#file_' + this.$attrs.id).click();
    },
    doImageSelected: function(name){

      console.log(name);

      this.imageSelectedName = name;
    },
    doImageUnselected: function(){

      this.imageSelectedName = "";
    },
    doAcceptSelectedImage: function(){
      
      this.doCloseImageSelectorSlide();

      this.$set(this.images, this.imageSelectedIndex, this.imageSelectedName);
      
      _.each(_.values(this.images), function(value, key, list){

        console.log($('option[value=' + value + ']'));

        $('option[value=' + value + ']').attr('selected','selected');
      });
    },
    doRemoveSelectedImage: function(index){
      console.log("doRemoveSelectedImage", index);
      var newSelected = {};
      console.log("newSelected", newSelected);
      _.each(this.images, function(value, key, list){

        console.log("image - compare", key, value, index, key != index);

        if(key != index){

          newSelected[key] = value;
        }
      });
      
      console.log("newSelected", newSelected);
      this.$set(this, "images", newSelected);
      console.log("this.images", this.images);

      _.each(_.values(this.images), function(value, key, list){

        console.log($('option[value=' + value + ']'));

        $('option[value=' + value + ']').attr('selected','selected');
      });
      
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

        _self.doActiveUploadButton();
        _self.doDisabledAceptarButton();
      };

      reader.readAsDataURL(file);
    },
    doUploadImage: function (event){

      console.log("doUploadImage", event);
      
      var serviceModel      = this.doService('doGetModel');
      var uploadImagePath   = this.uploadImagePath;

      if(serviceModel){

        uploadImagePath     = this.uploadImagePath  + "/" + serviceModel;
      }

			var serviceOptions = {
				data: {
          name:this.uploadImageName,
          description:this.uploadImageDescription,
          mimetype:this.uploadImageTypemime,
          extension:this.uploadImageExtension,
          path:uploadImagePath,
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
      
      if(response.data.status == "OK"){

        this.doActiveAceptarButton();

        this.doImageSelected(data.id);
      }
    },
    onUploadImageError: function (response, message){

			console.log("UPLOAD ERROR", response, message);
    },
    doGallerySelectImage: function(id){

      this.doUnselectedAllGalleryButton();
      this.doSelectedGalleryButton(id);

      this.doActiveAceptarButton();

      this.doImageSelected(id);

    },
    doUnselectedAllGalleryButton: function(){
      
      var _self   = this;

      _.each(_self.galleryData, function(value, key, list){

        _self.doUnselectedGalleryButton(value._id);
      });
    },
    doSelectedGalleryButton: function(id){
      
      $('#gallery_button_' + id).addClass("galleryButtonSelected");
      $('#gallery_button_' + id).removeClass("galleryButton");
    },
    doUnselectedGalleryButton: function(id){
      console.log("unselected", id);
      $('#gallery_button_' + id).removeClass("galleryButtonSelected");
      $('#gallery_button_' + id).addClass("galleryButton");
    },
    doGalleryPage: function (event){

      console.log("doGalleryPage", event);

			var serviceOptions = {
				data: {
          page:this.galleryPage,
          rows:this.galleryRows
				},
				successcbk:this.onGalleryPageSuccess,
				errorcbk:this.onGalleryPageError,
        loading:true,
        emitter:$("#gallery_" + this.$attrs.id)
			}

			this.loadService("gallery", serviceOptions);
		},
		onGalleryPageSuccess: function (response, data){
      var _self   = this;
      console.log("UPLOAD SUCCESS", response, data);
      
      if(response.data.status == "OK"){

        this.galleryData = [];

        _.each(data.objects, function(value, key, list){

          _self.galleryData.push(value);
        });
      }
    },
    onGalleryPageError: function (response, message){

			console.log("UPLOAD ERROR", response, message);
    },
    doActiveUploadButton: function (){

      $("#upload_button_" + this.$attrs.id).removeClass("disabled");
    },
    doDisabledUploadButton: function (){

      $("#upload_button_" + this.$attrs.id).addClass("disabled");
    },
    doActiveAceptarButton: function (){

      $("#aceptar_button_" + this.$attrs.id).removeClass("disabled");
    },
    doDisabledAceptarButton: function (){

      $("#aceptar_button_" + this.$attrs.id).addClass("disabled");
    },
    doValidateField: function (){

      return this.validateDefinitions();
    },
    hasImages : function(){

      return (_.keys(this.images).length > 0) ? true : false ;
    },
    imagesMaxIndex : function(){

      return _.keys(this.images).length - 1;
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
  template: "#___idReference_-template"
});
