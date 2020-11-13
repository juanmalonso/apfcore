Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;
  },
  methods: {
    setFieldValue: function (value) {
      var self = this;

      if(self.getTypeOption('schema') != undefined){

        var jsoneditorFormOptions = {
          show_errors: "never",
          collapsed: false,
          disable_array_reorder: true,
          disable_array_delete_all_rows:true,
          disable_array_delete_last_row:true,
          disable_edit_json:true,
          disable_collapse:true,
          disable_properties:true,
          schema: self.getTypeOption("schema")
        }
    
        if(value != null){
    
          jsoneditorFormOptions.startval = value;
        }
        
        self.jsoneditor = new JSONEditorForm(self.$refs.editor, jsoneditorFormOptions);
        
        self.jsoneditor.on('change',function() {
          
          self.doStylizeEditor();
          self.doGetData();
        });
    
        $("#" + this.field.id + "_field").val(JSON.stringify(value, null, 2));
      }
    },
    doGetData: function(){

      $("#" + this.field.id + "_field").val(JSON.stringify(this.jsoneditor.getValue(), null, 2));
    },
    doStylizeEditor: function(){
      var self = this;

      //TEXT BOXES
      $("#" + this.field.id + "_editor input[type='text']").parent().addClass("ui input");
      $("#" + this.field.id + "_editor input[type='text']").css("width","300px");
      

      //TEXTAREAS
      $("#" + this.field.id + "_editor textarea").parent().addClass("ui input");
      $("#" + this.field.id + "_editor textarea").attr("rows","3");
      $("#" + this.field.id + "_editor textarea").attr("cols","30");
      $("#" + this.field.id + "_editor textarea").css("height","auto");

      //NUMBER BOXES
      $("#" + this.field.id + "_editor input[type='number']").parent().addClass("ui input");
      $("#" + this.field.id + "_editor input[type='number']").css("text-align", "right");

      //BUTTONS
      $("#" + this.field.id + "_editor button[type='button']").each(function (){

        //REMOVE ROW
        if($(this).hasClass('json-editor-btn-delete')){

          $(this).addClass("ui icon red button");
          $(this).html("<i class='minus icon'></i>");
        }

        //ADD ROW
        if($(this).hasClass('json-editor-btn-add')){

          $(this).addClass("ui icon green button");
          $(this).html("<i class='plus icon'></i>");
        }

      });

      $("#" + this.field.id + "_editor select").each(function (){

        $(this).dropdown();
      });

      $("#" + this.field.id + "_editor input[type='checkbox']").each(function (){

        $(this).parent().addClass("ui toggle checkbox");
        $(this).parent().checkbox();
      });

      $("#" + this.field.id + "_editor div").each(function (){

        if($(this).hasClass('errmsg')){

          $(this).css( "display", "none" );
        }
      });

      $("#" + this.field.id + "_editor h3").css( "display", "none" );

      //DATE TIME BOX
      
      $("#" + this.field.id + "_editor input[isDateTime='true']").each(function (){

        console.log("Changed!!");

        var fieldId = self.field.id + "_" + $(this).attr('name');
        
        if(!_.has(self.jsonfieldschanged,fieldId)){

          console.log(fieldId, $(this));
          
          $(this).parent().addClass("ui left icon input");
          $(this).parent().prepend($("<i class='calendar blue icon'></i>"));
          $(this).parent().find("label").css( "display", "none" );
          
          $(this).flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
          });

          $(this).css("max-width","170px");

          self.jsonfieldschanged[fieldId] = true;
        }
        
      });
      
      $("#" + this.field.id + "_editor div[class='row']").css("margin-bottom","40px");
    },
    getFieldValue: function () {

      return $("#" + this.field.id + "_field").val();
    }
  },
  computed: {
    forForm:function(){
      return this.getScopeData("forForm", this.$attrs.forForm);
    },
    field:function(){
      return this.getScopeData("field", this.$attrs.field);
    },
    fieldIndex:function(){
      return this.getScopeData("fieldIndex", this.$attrs.fieldIndex);
    },
    data:function(){
      return this.getScopeData("data", this.$attrs.data);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps", this.$attrs.urlMaps);
    }
  },
  template: "#___idReference_-template"
});
