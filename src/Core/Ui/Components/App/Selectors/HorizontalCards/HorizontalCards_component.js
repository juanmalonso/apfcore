Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;

    //console.log($('#el_dimmable').dimmer('hide'));
    
    $('.dropdown').dropdown();
    
  },
  methods: {
    onSelectLinkOption: function (linkIndex, data, event){

      optionSelected        = this.linksFields[linkIndex].options[event.target.value];
      if(optionSelected.type == "link"){

        window.location     = this.parseStringBlocks(optionSelected.urlMap, data);
      }else if(optionSelected.type == "actions"){

        this.doComponentAction(optionSelected.actions, data, event);
      }
    },
    doCheckCondition: function (condition, data){
      
      result = true;

      if(condition != null){

        var parseredCondition = this.parseStringBlocks(condition, data);

        result = eval(parseredCondition);
      }

      return result;
    },
    doGetImageUrl: function (image){

      return this.basepath + "image/sq50/" + image + ".jpg";
    },
    doLocalTest: function (params, event) {

      console.log("doLocalTest", params, event);
    },
    goToPage: function (params, event) {

      var pageParams = {
        "datasource": this.datasource,
        "keyword": "*",
        "filters": [],
        "orders": [],
        "page": params.page,
        "rows": params.rows,
        "successcbk": this.onLoadPageDataSuccess,
        "errorcbk": this.onLoadPageDataError,
        "loadingstartcbk": this.onStartLoadingDimmer,
        "loadingendcbk": this.onEndLoadingDimmer
      }

      this.doService("doLoadData", pageParams, {}, event);
    },
    onLoadPageDataSuccess: function (response, data) {

      console.log("onLoadPageDataSuccess", data);

      this.data.objects = data.objects;
      this.data.page = data.page;
      this.data.rows = data.rows;
      this.data.pages = data.pages;
      this.paginator.actions[0].params.page = "%(data:page)%";
      this.paginator.actions[0].params.rows = "%(data:rows)%";

    },
    onLoadPageDataError: function (response, message) {

      console.log("onLoadPageDataError", response, message);
    },
    onStartLoadingDimmer: function (emitter) {
      //console.log("onStartLoadingDimmer", $('#el_dimmable'));
      $('#el_dimmable').dimmer('show');
    },
    onEndLoadingDimmer: function (emitter) {
      //console.log("onEndLoadingDimmer", $('#el_dimmable'));
      $('#el_dimmable').dimmer('hide');
    },
    getImagePath: function (data){

      var set     = false;
      var result  = this.basepath;

      if(!set && _.has(data, "avatar")){

        result    = result + "avatar/sw160/" + data.avatar.image + ".jpg";

        set       = true;
      }

      if(!set && _.has(data, "imagen")){

        result    = result + "imagen/sw160/" + data.imagen.image + ".jpg";

        set       = true;
      }

      return result;
    },
    getName: function (data){

      var set     = false;
      var result  = "";

      _.each(this.tableFields , function (value, key, list){

        if(value.renderType == "LINK"){

          result  = (set) ? result + " " + data[key] : result + data[key];
          set     = true;
        }
      });

      return result;
    },
    hasImageField: function(){
      var result = false;
      
      _.each(this.tableFields , function (value, key, list){

        if(!result && (value.renderType == "IMAGE" || value.renderType == "AVATAR")){

          result = true;
        }
      });
      
      return result;
    },
    hasStateField: function(){
      var result = false;
      
      _.each(this.tableFields , function (value, key, list){

        if(!result && (value.renderType == "STATE")){

          result = true;
        }
      });
      
      return result;
    },
    getStateField: function (){

      var result = false;
      
      _.each(this.tableFields , function (value, key, list){

        if(!result && (value.renderType == "STATE")){

          result = key;
        }
      });
      
      return result;
    },
  },
  computed: {
    paginatorPrevPage() {

      var result = false;

      if (this.data.pages > 1) {

        if (this.data.page > 1) {

          result = parseInt(this.data.page, 10) - 1;
        }
      }

      return result;
    },
    paginatorNextPage() {

      var result = false;

      if (this.data.pages > 1) {

        if (this.data.page < this.data.pages) {

          result = parseInt(this.data.page, 10) + 1;
        }
      }

      return result;
    },
    paginatorButtons: function () {
      var result = [];

      var i = 1;
      while (i <= this.data.pages && i <= 15) {

        result.push(i);

        i++;
      }

      return result;
    }
  },
  template: "#___tag_-template"
});
