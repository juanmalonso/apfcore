Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    var value = (this.$attrs.default == "") ? null : this.$attrs.default;

    if (_.has(this.$attrs.data, this.$attrs.id)) {

      value = _self.$attrs.data[this.$attrs.id];
    }
    
    //TODO : ver la logica de DATA edition

    /*
    const container = this.$refs.feditor
    const options   = {
        onChange: function () {

          $('#field_' + _self.$attrs.id).val(JSON.stringify(_self.editor.get(), null, 2));
        },
        "schema":_self.$attrs.options.schema,
        "modes": ['code', 'form', 'tree'],
        "mode": 'code'
    }

    this.editor = new JSONEditor(container, options);
    this.editor.set(value);
    $('#field_' + _self.$attrs.id).val(JSON.stringify(value, null, 2));
    */

    var jsoneditorFormOptions = {
      show_errors: "never",
      collapsed: false,
      disable_array_reorder: true,
      disable_array_delete_all_rows:true,
      disable_array_delete_last_row:true,
      disable_edit_json:true,
      disable_collapse:true,
      disable_properties:true,
      schema: _self.$attrs.options.schema
    }

    if(value != null){

      jsoneditorFormOptions.startval = value;
    }
    
    _self.jsoneditor = new JSONEditorForm(_self.$refs.editor, jsoneditorFormOptions);
    
    _self.jsoneditor.on('change',function() {
      
      _self.doStylizeEditor();
      _self.doGetData();
    });

    $('#field_' + _self.$attrs.id).val(JSON.stringify(value, null, 2));
    
  },
  methods: {
    doGetData: function(){

      $('#field_' + this.$attrs.id).val(JSON.stringify(this.jsoneditor.getValue(), null, 2));
    },
    doStylizeEditor: function(){
      var _self = this;

      //TEXT BOXES
      $("#editor_" + this.$attrs.id +" input[type='text']").parent().addClass("ui input");
      $("#editor_" + this.$attrs.id +" input[type='text']").css("width","300px");
      

      //TEXTAREAS
      $("#editor_" + this.$attrs.id +" textarea").parent().addClass("ui input");
      $("#editor_" + this.$attrs.id +" textarea").attr("rows","3");
      $("#editor_" + this.$attrs.id +" textarea").attr("cols","30");
      $("#editor_" + this.$attrs.id +" textarea").css("height","auto");

      //NUMBER BOXES
      $("#editor_" + this.$attrs.id +" input[type='number']").parent().addClass("ui input");
      $("#editor_" + this.$attrs.id +" input[type='number']").css("text-align", "right");

      //BUTTONS
      $("#editor_" + this.$attrs.id +" button[type='button']").each(function (){

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

      $("#editor_" + this.$attrs.id +" select").each(function (){

        $(this).dropdown();
      });

      $("#editor_" + this.$attrs.id +" input[type='checkbox']").each(function (){

        $(this).parent().addClass("ui toggle checkbox");
        $(this).parent().checkbox();
      });

      $("#editor_" + this.$attrs.id +" div").each(function (){

        if($(this).hasClass('errmsg')){

          $(this).css( "display", "none" );
        }
      });

      $("#editor_" + this.$attrs.id +" h3").css( "display", "none" );

      //DATE TIME BOX
      
      $("#editor_" + this.$attrs.id +" input[isDateTime='true']").each(function (){

        console.log("Changed!!");

        var fieldId = _self.$attrs.id + "_" + $(this).attr('name');
        
        if(!_.has(_self.jsonfieldschanged,fieldId)){

          console.log(fieldId, $(this));
          
          $(this).parent().addClass("ui left icon input");
          $(this).parent().prepend($("<i class='calendar blue icon'></i>"));
          $(this).parent().find("label").css( "display", "none" );
          
          $(this).flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
          });

          $(this).css("max-width","170px");

          _self.jsonfieldschanged[fieldId] = true;
        }
        
      });
      
     $("#editor_" + this.$attrs.id +" div[class='row']").css("margin-bottom","40px");
    },
    getFieldValue: function () {

      console.log($('#field_' + this.$attrs.id).val());
      return $('#field_' + this.$attrs.id).val();
    },
    doValidateField: function () {

      return this.validateDefinitions();
    }
  },
  computed: {
  },
  template: "#___idReference_-template"
});
