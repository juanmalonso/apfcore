Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;

    console.log(this.selectorLinks,this.selectorLinks.length);
  },
  methods: {
    getLinkMappedUrl:function (fieldId, rowData){

      //TODO : MAPPING URL
      return this.fields[fieldId].render.url;
    },
    isRenderTypeField:function (renderType,fieldId){
      
      var result = false;

      if(this.fields[fieldId] != undefined){

        if(this.fields[fieldId].render != undefined){

          if(this.fields[fieldId].render.type == renderType){
  
            result = true;
          }
        }
      }
      console.log(renderType, fieldId, result);
      return result;
    },
    isListableField:function (fieldId){
      
      var result = false;

      if(this.fields[fieldId] != undefined){

        if(this.fields[fieldId].render != undefined){

          if(this.fields[fieldId].render.type == "value" || this.fields[fieldId].render.type == "link"){
  
            result = true;
          }
        }
      }

      return result;
    }
  },
  computed: {
    selectorFields : function(){
      var result = [];

      _.each(this.fields, function (element, index, list){
        
        if(element.render.type == "link" || element.render.type == "value"){
          result[index] = element;
        }
      });

      return result;
    },

    selectorLinks : function(){
      var result = [];

      _.each(this.fields, function (element, index, list){
        
        if(element.render.type == "buttonlink"){
          result[index] = element;
        }
      });

      return result;
    },

    selectorActions : function(){
      var result = [];

      _.each(this.fields, function (element, index, list){
        
        if(element.render.type == "buttonaction"){
          result[index] = element;
        }
      });

      return result;
    }
  },
  template: "#___tag_-template"
});
