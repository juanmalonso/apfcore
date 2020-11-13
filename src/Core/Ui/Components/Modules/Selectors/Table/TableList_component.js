Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    
  },
  methods: {
    onAttrWatchers: function(){
            
      this.modLoadDataService(this.generateDataServiceOptions({}),false);
    },
    isFieldRender: function (fieldData) {
      var result = false;

      if (!fieldData.uiOptions.hidden) {

        if (fieldData.uiOptions.listable){

          result = true;
        }
      }

      return result;
    },
    hasRowLinks: function(){
      var result = false;
      
      if(_.size(this.rowLinks) > 0){

        result = true;
      }

      return result;
    },
    hasRowActions: function(){
      var result = false;

      if(_.size(this.rowActions) > 0){

        result = true;
      }

      return result;
    },
    hasActions: function(){
      var result = false;

      if(_.size(this.actions) > 0){

        result = true;
      }

      return result;
    },
    doGotoPage: function (params, event) {
      var _self = this;

      _.each(params, function (value, key) {

        Vue.set(_self.dataService.params, key, value);
      });

      this.modLoadDataService(this.generateDataServiceOptions({}), false);
    }
  },
  computed: {
    paginatorPrevPage() {

      var result = false;

      if (this.pages > 1) {

        if (this.page > 1) {

          result = parseInt(this.page, 10) - 1;
        }
      }

      return result;
    },
    paginatorNextPage() {

      var result = false;

      if (this.pages > 1) {

        if (this.page < this.pages) {

          result = parseInt(this.page, 10) + 1;
        }
      }

      return result;
    },
    paginatorButtons: function () {
      var result = [];

      var i = 1;
      while (i <= this.pages && i <= 15) {

        result.push(i);

        i++;
      }

      return result;
    },
    model:function(){
      return this.getScopeData("model");
    },
    fields:function(){
      return this.getScopeData("fields");
    },
    page:function(){
      return this.getScopeData("page");
    },
    rows:function(){
      return this.getScopeData("rows");
    },
    totals:function(){
      return this.getScopeData("totals");
    },
    pages:function(){
      return this.getScopeData("pages");
    },
    objects:function(){
      return this.getScopeData("objects");
    },
    facets:function(){
      return this.getScopeData("facets");
    },
    rowLinks:function(){
      return this.getScopeData("rowLinks");
    },
    rowActions:function(){
      return this.getScopeData("rowActions");
    },
    actions:function(){
      return this.getScopeData("actions");
    },
    linkAction:function(){
      return this.getScopeData("linkAction");
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps");
    },
    options:function(){
      return this.getScopeData("options");
    }
  },
  template: "#___idReference_-template"
});
