Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    
    $('.menu .item').tab();
  },
  methods: {
    generateReference: function(){
      var result  = this.referenceName;

      if (_.has(this.$attrs, 'id')) {

        result    = this.$attrs.id;
      }
      
      return result;
    },
    onAttrWatchers: function(){

      this.modLoadDataService(this.generateDataServiceOptions({}),false);
    },
    isCustomField: function(field){

      return (_.indexOf(this.customFields, field) != -1) ? true : false;
    },
    getFieldsReferences: function(){
      var self = this;
      var result = {};

      _.each(this.fields, function (value, key) {
        
        result[value.id]         = self.referenceName + "_form_" + value.id + "_field";
      });
      
      return result;
    },
    getGroupFields: function (group){
      var self    = this;
      var result  = {};

      _.each(self.fields, function (value, key) {

        if(value.group == group){

          result[key] = value;
        }
      });

      return result;
    },
    getFieldsGroups: function(){
      var self    = this;
      var result  = {};

      _.each(self.fields, function (value, key) {

        if(!_.has(result, value.group)){

          result[value.group] = {"key":value.group, "label":self.fieldsGroups[value.group]};
        }
      });

      return result;
    },
    setTab: function(id){
      
      $("#" + id).tab();
    },
  },
  computed: {
    model:function(){
      return this.getScopeData("model");
    },
    actions:function(){
      return this.getScopeData("actions");
    },
    options:function(){
      return this.getScopeData("options");
    },
    fields:function(){
      return this.getScopeData("fields");
    },
    fieldsGroups:function(){
      return this.getScopeData("fieldsGroups");
    },
    objects:function(){
      return this.getScopeData("objects");
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps");
    }
  },
  template: "#___idReference_-template"
});
