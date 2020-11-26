Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;
    
    self.registerComponent();
  },
  methods: {
    onAttrWatchers: function(){

    },
    doReset: function(){
      var self = this;

      _.each(this.getFiltersReferences(), function (value, key, list){

        if(self.hasByReferenceName(value)){
        
          (self.getByReferenceName(value)).doReset();
        }
      });
    },
    doFilter: function(){
      var self = this;

      var dataServiceData = {
        "filters":this.recopileFiltersData()
      };

      var overwriteDataServiceOptions           = {};
      overwriteDataServiceOptions.data          = dataServiceData;

      this.$parent.modLoadDataService(this.$parent.generateDataServiceOptions(overwriteDataServiceOptions), false);
      
    },
    recopileFiltersData: function(){

      var self              = this;
      var result            = {};

      _.each(this.getFiltersReferences(), function (value, key, list){

        if(self.hasByReferenceName(value)){
        
          result[key] = (self.getByReferenceName(value)).getFilterValue();
        }
      });
      console.log("FILTERS DATA", result);
      return result;
    },
    getFiltersReferences: function(){
      var self = this;
      var result = {};

      _.each(this.filters, function (value, key) {
        
        result[key]         = self.referenceName + "_filters_" + key + "_filter";
      });
      
      return result;
    },
  },
  computed: {
    filters:function(){
      return this.getScopeData("filters",this.$attrs.filters);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps",this.$attrs.urlMaps);
    }
  },
  template: "#___idReference_-template"
});
