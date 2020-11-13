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
    getKanbanColumnData: function(columnid){
      var result = [];
      console.log("KANBAN COLUMNS DATA", columnid);
      //STATES
      if(_.has(this.options, "isStatesBoard")){
        if(this.options.isStatesBoard){

          _.each(this.objects, function (value, key) {

            if(value.objState == columnid){

              result.push(value);
            }
          });
        }
      }

      //OUTER FIELS COLUMN
      //TODO..

      return result;
    },
    getKanbanColumns: function(){
      var result = [];

      if(_.has(this.options, "isStatesBoard")){
        console.log("KANBAN COLUMNS", this.options.isStatesBoard);
        if(this.options.isStatesBoard){

          if(this.model.statesOptions.stateable){
            
            _.each(this.model.statesOptions.states, function (value, key) {
              
              result.push({"key":key, "label":value.name, "style":value.style});
            });
          }
        }
      }

      console.log(result);
      
      return result;
    }
  },
  computed: {
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
      return this.getScopeData("options", {});
    }
  },
  template: "#___idReference_-template"
});
