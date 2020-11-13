Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;

    $('#importer_sidebar').sidebar('setting', 'transition', 'overlay');
    $('#importer_sidebar').sidebar('setting', 'closable', false);

    $('.imageSelectorTab').on('click',function (event){

      console.log($(this).attr("data-tab"));

      /*
      if($(this).attr("data-tab") == 'gallery'){

        _self.doGalleryPage();
      }
      */
     
    });
  },
  methods: {

    doOpenImporterSidebar: function(){
      
      $('#importer_sidebar').sidebar('show');
    },
    doCloseImporterSidebar: function(){

      $('#importer_sidebar').sidebar('hide');
    },
    doImport: function(){

      console.log("import");
      $("#importerForm").submit();
    },
    doBrowseFile: function(){
      
      $('#importer_file').click();
    },
    onSelectedFile: function (event){
      var _self   = this;

      var file    = event.target.files[0];

      $("#upload_button").text(file.name);

      _self.doActiveAceptarButton();
    },
    doActiveAceptarButton: function (){

      $("#importer_aceptar_button").removeClass("disabled");
    },
    doDisabledAceptarButton: function (){

      $("#importer_aceptar_button").addClass("disabled");
    },
  },
  computed: {
  },
  template: "#___idReference_-template"
});
