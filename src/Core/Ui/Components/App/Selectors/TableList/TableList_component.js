Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;

    console.log(this.selectorFields);
    console.log(this.selectorLinks);
    console.log(this.selectorActions);
  },
  methods: {},
  computed: {
    selectorFields : function(){
      var result = {};

      _.each(this.fields, function (element, index, list){
        
        if(element.render.type == "link" || element.render.type == "value"){
          result[index] = element;
        }
      });

      return result;
    },
    selectorLinks : function(){
      var result = {};

      _.each(this.fields, function (element, index, list){
        
        if(element.render.type == "buttonlink"){
          result[index] = element;
        }
      });

      return result;
    },
    selectorActions : function(){
      var result = {};

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
