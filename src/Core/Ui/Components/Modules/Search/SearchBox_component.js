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
    doSearch: function(){
      var self = this;

      var dataServiceData = {
        "keyword":this.getKeyword()
      };

      var overwriteDataServiceOptions           = {};
      overwriteDataServiceOptions.data          = dataServiceData;

      this.$parent.modLoadDataService(this.$parent.generateDataServiceOptions(overwriteDataServiceOptions), false);
      
    },
    getKeyword: function(){

      if($(this.$refs.realField).val() == ""){

        $(this.$refs.realField).val("*");
      }
      
      return $(this.$refs.realField).val();
    }
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
