Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;
  },
  methods: {
    initField: function () {
      var newItems = [];
      var excludeValues = [];

      if (this.getTypeOption("excludeValues") != undefined) {

        excludeValues = this.getTypeOption("excludeValues");
      }

      if (this.field.type == "options") {

        if (this.getTypeOption("data") != undefined) {

          _.each(this.getTypeOption("data"), function (value, key, list) {

            if (_.indexOf(excludeValues, value.value) == -1) {

              newItems.push({ "label": value.label, "value": value.value });
            }
          });

          this.setScopeData("items", newItems);
        }
      }

      if (this.field.type == "objectr" || this.field.type == "objectsr") {

        if (this.getTypeOption("model") != undefined) {

          var newDataService = {
            "type": "service",
            "name": "objects-list",
            "scope": "service",
            "params": {
              "rows": 1000
            }
          };

          if (this.getTypeOption("remoteSearch") != undefined) {

            var minSearchCharacters = 3;

            if (this.getTypeOption("minCharacters") != undefined) {

              minSearchCharacters = this.getTypeOption("minCharacters");
            }

            var saveData = true;

            if (this.getTypeOption("saveRemoteData") != undefined) {

              saveData = this.getTypeOption("saveRemoteData");
            }

            $(this.$refs.realField).dropdown({
              minCharacters: minSearchCharacters,
              saveRemoteData: false,
              apiSettings: {
                url: this.getScopeDataServiceUrl("_service", "objects-list-query") + "/model:" + this.getTypeOption("model") + "/query:{query}"
              }
            });

            newDataService = {
              "type": "service",
              "name": "objects-list",
              "scope": "service",
              "params": {
                "rows": 1000
              }
            };

            var value = this.field.defaultValue;

            if (_.has(this.data, this.field.id)) {

                value = this.data[this.field.id];
            }

            var selectedItems = (_.isArray(value)) ? value : [value];

            newDataService.params.hardFilters = {"_id":selectedItems};

            console.log("REMOTEEDIT", selectedItems);

          }else{

            newDataService = {
              "type": "service",
              "name": "objects-list",
              "scope": "service",
              "params": {
                "rows": 1000
              }
            };

            if (this.getTypeOption("hardFilters") != undefined) {

              newDataService.params.hardFilters = this.getTypeOption("hardFilters");
            }
          }

          newDataService.params.model = this.getTypeOption("model");

          var cacheKey = "object_name_and_image_" + this.getTypeOption("model");
          cacheKey.replace("-", "_");

          Vue.set(this, 'dataService', newDataService);

          this.modLoadDataService(this.generateDataServiceOptions({}), false);

        }
      }

    },
    onBeforeUpdateScope: function (newDataScopeRegisterData) {

      console.log("BEFORE UPDATE SCOPE DATA", newDataScopeRegisterData);

      var newItems = [];
      var excludeValues = [];

      if (this.getTypeOption("excludeValues") != undefined) {

        excludeValues = this.getTypeOption("excludeValues");
      }

      if (_.has(newDataScopeRegisterData, "objects")) {

        _.each(newDataScopeRegisterData.objects, function (value, key, list) {

          if (_.indexOf(excludeValues, value.id) == -1) {

            newItems.push({ "label": value.name, "value": value.id });
          }

        });

        this.setScopeData("items", newItems);

        this.setFieldValue(this.getFieldValue());
      }

      newDataScopeRegisterData = [];

      return newDataScopeRegisterData;
    },
    setFieldValue: function (value) {
      var self = this;
      var selectedItems = (_.isArray(value)) ? value : [value];
      var dropdownValues = [];

      $(this.$refs.realField).empty();

      if (_.isArray(this.items)) {

        _.each(this.items, function (value, key, list) {

          var tempItem = { "name": value.label, "value": value.value };

          if (_.indexOf(selectedItems, value.value) !== -1) {

            tempItem['selected'] = true;
          }

          if (_.has(tempItem, "selected")) {

            $(self.$refs.realField).append('<option value="' + value.value + '" selected="true">' + value.label + '</option>');
          } else {

            $(self.$refs.realField).append('<option value="' + value.value + '">' + value.label + '</option>');
          }

          dropdownValues.push(tempItem);
        });
      }

      if (dropdownValues.length == 0 && selectedItems.length > 0) {

        _.each(selectedItems, function (value, key, list) {

          $(self.$refs.realField).append('<option value="' + value + '" selected="selected">' + value + '</option>');

          dropdownValues.push({ "name": value, "value": value, "selected": true });
        });
      }

      $(this.$refs.realField).dropdown('clear');

      $(this.$refs.realField).dropdown({
        values: dropdownValues
      });

      $(this.$refs.realField).dropdown("set selected", selectedItems);

      $(this.$refs.realField).val(selectedItems);
    },
    getFieldValue: function () {

      if($(this.$refs.realField).val() == null){

        return "";
      }else{

        return $(this.$refs.realField).val();
      }
    },
    isMultiple: function () {
      var result = false;

      if (this.field.type == "objectsr") {

        result = true;
      }

      if (this.getTypeOption('multiple') != undefined) {

        result = this.getTypeOption('multiple');
      }

      return result;
    }
  },
  computed: {
    forForm: function () {
      return this.getScopeData("forForm", this.$attrs.forForm);
    },
    field: function () {
      return this.getScopeData("field", this.$attrs.field);
    },
    fieldIndex: function () {
      return this.getScopeData("fieldIndex", this.$attrs.fieldIndex);
    },
    data: function () {
      return this.getScopeData("data", this.$attrs.data);
    },
    items: function () {
      return this.getScopeData("items", []);
    },
    urlMaps: function () {
      return this.getScopeData("urlMaps", this.$attrs.urlMaps);
    }
  },
  template: "#___idReference_-template"
});
