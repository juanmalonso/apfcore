Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;
    
    self.registerComponent();
    
    this.setRenderType();
  },

  methods: {
    onAttrWatchers: function(){
            
      this.setRenderType();
    },
    setRenderType: function(){

      var field = this.field;
      
      if(field.type == "text"){
        //TEXT || LINK || STATE
        if(field.isName == "1"){

          this.setScopeData("renderType", "LINK");
        }else if (field.isState == true){

          var state = this.getStateData(this.value);
          
          this.setMultipleScopeData({"value":this.value, "label":state.name, "style": state.style});

          this.setScopeData("renderType", "STATE");
        }else{

          this.setScopeData("renderType", "VALUE");
        }
      }else if(field.type == "integer"){

        //TODO: NUMBER FORMAT
        this.setScopeData("renderType", "VALUE");
      }else if(field.type == "datetime"){

        //TODO: DATE FORMAT (MOMENT JS)
        this.setScopeData("renderType", "VALUE");    
      }else if(field.type == "options"){

        //OPTION 
        if(_.has(field.typeOptions.multiple)){

          if(field.typeOptions.multiple == true){

            //MULTIPLE OPTIONS
            var labels      = this.getOptionLabel(this.value, field.typeOptions.data);
            this.setScopeData("renderType", "TAGS");

            var tags        = []

            _.each(labels, function (label, key) {

              tags.push({"label":label, "style":"basic"});
            });

            this.setMultipleScopeData({"tags": tags});
          }
        }else{
          
          this.setScopeData("renderType", "LABEL");
          console.log("OPTIONS TYPE OPTION LABEL",this.field.id, this.getOptionLabel(this.$attrs.value, field.typeOptions.data));
          
          this.setMultipleScopeData({"label": this.getOptionLabel(this.$attrs.value, field.typeOptions.data)});
        }
      }else if(field.type == "tags"){

        //TAGS
        this.setScopeData("renderType", "TAGS");

        var tags        = []

        _.each(this.$attrs.value, function (label, key) {

          tags.push({"label":label, "style":"blue"});
        });

        this.setMultipleScopeData({"tags": tags});
      }else if(field.type == "objectr"){

        if(_.has(field.typeOptions, "model")){
          //IMAGE
          if(field.isImage == "1"){
            
            this.setScopeData("renderType", "IMAGE");

            if(field.typeOptions.model == "avatar"){

              this.setMultipleScopeData({"src": this.parseStringBlocks(this.urlMaps['AVATAR'], this.dataRow)});
            }else if(field.typeOptions.model == "image"){

              this.setMultipleScopeData({"src": this.parseStringBlocks(this.urlMaps['IMAGE'], this.dataRow)});
            }
          }else{

            this.setScopeData("renderType", "OBJECTNAME");

            if(_.has(this.$attrs,"forceRenderType")){

              this.setScopeData("renderType", this.$attrs.forceRenderType);
            }

            //CACHE
            var cacheKey        = "object_name_and_image_" + field.typeOptions.model + "_" + this.value;
            cacheKey.replace("-", "_");

            var newDataService = {
                  "type":"service",
                  "name":"object-name-and-image",
                  "scope":"service",
                  "params": {
                    "model":field.typeOptions.model,
                    "id":this.value
                  }
            };

            console.log("SET NEW DATA SERVICE", newDataService);

            Vue.set(this, 'dataService', newDataService);
              
            this.modLoadDataService(this.generateDataServiceOptions({}), cacheKey);
          }
        }
      }
    },
    getStateData: function (pstate){
      var result        = null;
      var statesOptions = this.model.statesOptions;

      if(_.has(statesOptions, "stateable")){

        if(statesOptions.stateable == true){

          _.each(statesOptions.states, function (state, key) {

            if(key == pstate){

              result = state;
            }
          });
        }
      }

      return result;
    },
    getMultipleOptionLabel: function(value, data){
      var result = [];

      _.each(data, function (option, key) {

        if(_.indexOf(value, option.value) != -1){

          result.push(option.label);
        }
      });

      return result;
    },
    getOptionLabel: function(value, data){
      var result = null;
      
      _.each(data, function (option, key) {

        if(result == null){

          if(option.value == value){

            result = option.label;
          }
        }
      });
      
      return result;
    },
    getImageObjectData: function(){
      return {
        "avatar":this.image,
        "image":this.avatar
      };
    }
  },
  computed: {
    fieldIndex:function(){
      return this.getScopeData("fieldIndex",this.$attrs.fieldIndex);
    },
    model:function(){
      return this.getScopeData("model",this.$attrs.model);
    },
    field:function(){
      return this.getScopeData("field",this.$attrs.field);
    },
    value:function(){
      return this.getScopeData("value",this.$attrs.value);
    },
    dataRow:function(){
      return this.getScopeData("dataRow",this.$attrs.dataRow);
    },
    linkAction:function(){
      return this.getScopeData("linkAction",this.$attrs.linkAction);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps",this.$attrs.urlMaps);
    },
    icon:function(){
      return this.getScopeData("icon","");
    },
    renderType:function(){
      return this.getScopeData("renderType", "none");
    },
    label:function(){
      return this.getScopeData("label","");
    },
    style:function(){
      return this.getScopeData("style","");
    },
    tags:function(){
      return this.getScopeData("tags","");
    },
    src:function(){
      return this.getScopeData("src","");
    },
    image:function(){
      return this.getScopeData("image","");
    },
    name:function(){
      return this.getScopeData("name","");
    },
    icon:function(){
      return this.getScopeData("icon","");
    },
    label:function(){
      return this.getScopeData("label","");
    }
  },
  template: "#___idReference_-template"
});
