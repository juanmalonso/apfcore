Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    var selected = this.$attrs.default;

    if (_.has(this.$attrs.options, "data")) {

      this.items = this.$attrs.options.data;
    }

    if (_.has(this.$attrs.data, this.$attrs.id)) {

      selected = this.$attrs.data[this.$attrs.id];
    }
    
    //FIELD
    if (this.$attrs.editAs == "FIELD") {

      this.doSetDropdown(this.items, selected);

    } else if (this.$attrs.editAs == "LIST") {

      $('.ui.checkbox').checkbox();
      $('.ui.radio.checkbox').checkbox();

      _.each(selected, function (value, key, list) {

        $("input[value='" + value + "']").parent().checkbox('check');
      });
    }

    if (_.has(this.$attrs.options, "isAsync")) {

      if (_.has(this.$attrs.options, "dependsOn")) {

        if (this.$attrs.options.isAsync) {

          var componente = this.getFieldByReference(this.$attrs.options.dependsOn);

          componente.$on('do-change', function (obj) {

            this.doFilterData(obj, _self);
          });

          $(document).ready(function (){

            _self.doFilterData({"params":componente.getFieldValue(), "component": componente}, _self);
          });
        }
      }
    }

  },
  methods: {
    doSetDropdown: function (values, selectedItems) {
      var _self = this;
      var dropdownValues = [];
      
      $('#field_' + _self.$attrs.id).empty();

      _.each(values, function (value, key, list) {

        var tempItem = { "name": value.label, "value": value.value };

        if(_.indexOf(selectedItems, value.value) !== -1){

          tempItem['selected'] = true;
        }

        dropdownValues.push(tempItem);

        if(_.has(tempItem,"selected")){

          $('#field_' + _self.$attrs.id).append( '<option value="'+value.value+'" selected="true">'+value.label+'</option>' );
        }else{

          $('#field_' + _self.$attrs.id).append( '<option value="'+value.value+'">'+value.label+'</option>' );
        }
      });

      if(dropdownValues.length == 0 && selectedItems.length > 0){

        _.each(selectedItems, function (value, key, list) {

          $('#field_' + _self.$attrs.id).append( '<option value="'+value+'" selected="selected">'+value+'</option>' );
        });
      }
      
      $('#field_' + _self.$attrs.id).dropdown({
        values: dropdownValues
      });

      /*$('#field_' + _self.$attrs.id).dropdown("setup menu", {

        values: dropdownValues
      });*/

      //$('#field_' + _self.$attrs.id).dropdown("set text","");

      $('#field_' + _self.$attrs.id).dropdown("set selected", selectedItems);
      
      $('#field_' + _self.$attrs.id).val(selectedItems);

      /*
      {
        onChange: function (value, text, $selectedItem) {

          console.log(_self);
          //_self.$emit('do-change', _self.getFieldValue());
        },
        onRemove: function (value, text, $selectedItem) {

          console.log(_self);
          //_self.$emit('do-change', _self.getFieldValue());
        }
      }
      */

      $('#field_' + _self.$attrs.id).on('change', function (event){

        _self.$emit('do-change', {"params":_self.getFieldValue(), "component": _self});
      });

      console.log("SET DROP DOWN", _self.$attrs.id, values, selectedItems, _self.getFieldValue());
    },
    doFilterData: function (obj, _self) {
      var _self   = _self;
      var filters = {};

      if (_.isArray(obj.params) && !_.isEmpty(obj.params)) {

        filters[_self.$attrs.options.filterField] = obj.params;
      } else if (_.isString(obj.params) && !_.isEmpty(obj.params)) {

        filters[_self.$attrs.options.filterField] = [obj.params];
      }

      if (!_.isEmpty(filters)) {

        var options = {
          model: _self.$attrs.options.model,
          filters: filters,
          rows: 1000,
          successcbk: _self.onFilterDataSuccess,
          errorcbk: _self.onFilterDataError
        }
        console.log("DATA", JSON.stringify(options));
        _self.doService("doLoadData", options);
      } else {

        _self.doSetDropdown([], []);
      }
      
    },
    onFilterDataSuccess: function (response, data) {

      var newItems = [];
      var selected = this.getFieldValue();

      if (data.objects.length > 0) {

        _.each(data.objects, function (value, key, list) {

          newItems.push({ "label": value.name, "value": value._id });
        });
      }

      if(selected == null){

        var selected = this.$attrs.default;

        if (_.has(this.$attrs.data, this.$attrs.id)) {

          selected = this.$attrs.data[this.$attrs.id];
        }
      }
      console.log("SELECTED", selected);
      this.doSetDropdown(newItems, selected);
    },
    onFilterDataError: function (response, message) {

      console.log(response, message);
    },
    getFieldValue: function () {

      var result = null;

      if (this.$attrs.editAs == "FIELD") {

        result = $('#field_' + this.$attrs.id).val();

      } else if (this.$attrs.editAs == "LIST") {
        result = [];

        $("input[name='" + this.$attrs.id + "']").each(function () {

          if ($(this).parent().checkbox("is checked")) {

            result.push($(this).val());
          }
        });

      }
      console.log("RESULT", result);
      return result;
    },
    doValidateField: function () {

      return this.validateDefinitions();
    },
    isMultiple: function () {
      var result = false;

      if (this.$attrs.options.multiple != undefined) {

        result = this.$attrs.options.multiple;
      }

      return result;
    }
  },
  computed: {
  },
  template: "#___idReference_-template"
});
