Vue.component("___tag_", {
  mixins: [nbsComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    var value = (this.$attrs.default == "") ? {} : this.$attrs.default;

    if(_.has(this.$attrs.data, this.$attrs.id)){
      
      value = _self.$attrs.data[this.$attrs.id];
    }
    
    //TODO : ver la logica de DATA edition

    const container = this.$refs.eleditor
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
  },
  methods: {
    getFieldValue: function () {
      
      return $('#field_' + this.$attrs.id).val();
    },
    doValidateField: function (){

      return this.validateDefinitions();
    },
    doEmpty: function(){

      this.editor.set({});
      $('#field_' + this.$attrs.id).val(JSON.stringify(this.editor.get(), null, 2));
    },
    doExpand: function(){

      this.editor.expandAll();
    },
    doCollapse: function(){

      this.editor.collapseAll();
    },
    doIndent: function(){

      this.editor.set(this.editor.get());
    },
    doMode: function(mode){

      this.editor.setMode(mode);
      this.mode = mode;
    }
  },
  computed: {
  },
  template: "#___tag_-template"
});
