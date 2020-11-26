Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self      = this;
    var newItems  = [];

    this.registerComponent();

    if(_.has(self.filter, "items")){

      newItems = self.filter.items;
    }

    if(_.has(self.filter, "model")){

      var newDataService        = {
        "type":"service",
        "name":"objects-list",
        "scope":"service",
        "params": {
          "model": self.filter.model,
          "rows":1000
        }
      };

      var cacheKey        = "object_name_and_image_" + self.filter.model;
      cacheKey.replace("-", "_");

      Vue.set(this, 'dataService', newDataService);
          
      this.modLoadDataService(this.generateDataServiceOptions({}), false);
    }

    this.setScopeData("items", newItems);

    this.setDropdown();
  },
  methods: {
    onBeforeUpdateScope: function (newDataScopeRegisterData){

      var newItems = [];

      if(_.has(newDataScopeRegisterData,"objects")){

        _.each(newDataScopeRegisterData.objects, function (value, key, list) {

          newItems.push({"label":value.name, "value":value.id});
        });

        this.setScopeData("items", newItems);

        this.setDropdown();
      }

      return newDataScopeRegisterData;
    },
    doReset: function(){

      this.setDropdown();
    },
    setDropdown: function(){
      var self = this;
      var selectedItems   = [];
      var dropdownValues  = [];

      if(_.has(self.filter, "value")){

        selectedItems = (_.isArray(this.filter.value)) ? this.filter.value : [this.filter.value];
      }

      $(this.$refs.realDropdown).empty();

      if(_.has(self.filter, "label")){

        $(self.$refs.realDropdown).append( '<option value="">'+self.filter.label+'</option>' );
      }

      if(_.isArray(this.items)){

        _.each(this.items, function (value, key, list) {

          var tempItem = { "name": value.label, "value": value.value };

          if(_.indexOf(selectedItems, value.value) !== -1){

            tempItem['selected'] = true;
          }

          if(_.has(tempItem,"selected")){

            $(self.$refs.realDropdown).append( '<option value="'+value.value+'" selected="true">'+value.label+'</option>' );
          }else{

            $(self.$refs.realDropdown).append( '<option value="'+value.value+'">'+value.label+'</option>' );
          }

          dropdownValues.push(tempItem);
        });
      }

      if(dropdownValues.length == 0 && selectedItems.length > 0){

        _.each(selectedItems, function (value, key, list) {

          $(self.$refs.realDropdown).append( '<option value="'+value+'" selected="selected">'+value+'</option>' );

          dropdownValues.push({ "name": value, "value": value, "selected":true });
        });
      }

      $(this.$refs.realDropdown).dropdown('clear');

      $(this.$refs.realDropdown).dropdown({
        values: dropdownValues
      });

      $(this.$refs.realDropdown).dropdown("set selected", selectedItems);
      
      $(this.$refs.realDropdown).val(selectedItems);

    },
    getFilterValue: function () {
      //console.log("FILTER VALUE", this.filterIndex, $(this.$refs.realDropdown).val());
      return $(this.$refs.realDropdown).val();
    },
  },
  computed: {
    filter:function(){
      return this.getScopeData("filter", this.$attrs.filter);
    },
    filterIndex:function(){
      return this.getScopeData("filterIndex", this.$attrs.filterIndex);
    },
    items:function(){
      return this.getScopeData("items", []);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps", this.$attrs.urlMaps);
    }
  },
  template: "#___idReference_-template"
});
