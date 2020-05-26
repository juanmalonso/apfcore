Vue.component("___tag_", {
  mixins: [nbsComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    var value = (this.$attrs.default == "") ? {} : this.$attrs.default;

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
    console.log($('#editor_variaciones'));
    //$(document).ready(function () {
      _self.jsoneditor = new JSONEditor(_self.$refs.editor, {
        schema: {
          "type": "array",
          "format":"table",
          "items":{
            "type":"object",
            "properties":{
              "descripcion":{
                "type":"string",
                "options": {
                  "inputAttributes": {
                    "class": "ui input"
                  }
                }
              },
              "precio":{
                "type":"number"
              }
            }
          }
        }
      });
    //});
  },
  methods: {
    getFieldValue: function () {

      return $('#field_' + this.$attrs.id).val();
    },
    doValidateField: function () {

      return this.validateDefinitions();
    }
  },
  computed: {
  },
  template: "#___tag_-template"
});
