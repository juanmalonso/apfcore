Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;
  },
  methods: {
    doActivateTab: function(tabIndex){

      this.setScopeData("activetab",tabIndex)
    },
    doRenderedTabs: function(){

      $(document).ready(function (){

        $('.menu .item').tab();
      });
    },
    isOpenSlider: function (slider){
      var result = false;

      _.each(this.opensliders, function (value, key) {

        if(!result && value == slider){

          result = true;
        }
      });

      return result;
    },
    doOpenSlider: function (params){
      var self              = this;
      var tmp_opensliders   = this.opensliders;
      
      console.log("OPEN SLIDER", params.slider, "OLD OPEN SLIDERS", tmp_opensliders);

      this.maxzindex = this.maxzindex + 100;

      $("#" + params.slider + "_sidebar").css('z-index', this.maxzindex);

      $("#" + params.slider + "_sidebar").sidebar('setting', 'transition', 'overlay');
      console.log($("#" + params.slider + "_sidebar"));

      $("#" + params.slider + "_sidebar").sidebar('setting', 'closable', false);
      $("#" + params.slider + "_sidebar").sidebar('setting', 'exclusive', false);
      $("#" + params.slider + "_sidebar").sidebar('show');

      tmp_opensliders.push(params.slider);
      console.log("NEW OPEN SLIDERS", tmp_opensliders);

      this.setScopeData("opensliders",tmp_opensliders);
      this.setScopeData("slidersParams",params.slidersParams);
    },
    doCloseSlider: function (params){
      var tmp_opensliders   = [];

      console.log("CLOSE SLIDER", params.slider);

      $("#" + params.slider + "_sidebar").sidebar('hide');

      _.each(this.opensliders, function (value, key) {

        if(value != params.slider){

          tmp_opensliders.push(value);
        }
      });

      this.setScopeData("opensliders",tmp_opensliders);
    },
    getSliderDataService: function(sliderName, defaultDataService){
      var self = this;
      var result = defaultDataService;
      
      if(_.has(self.slidersParams,sliderName)){

        if(_.has(self.slidersParams[sliderName],'dataService')){

          result = self.slidersParams[sliderName]['dataService'];
        }
      }

      if(_.has(defaultDataService.params,'moduleAction')){

        result.params.moduleAction = defaultDataService.params.moduleAction;
      }

      if(_.has(defaultDataService.params,'actionLayout')){

        result.params.actionLayout = defaultDataService.params.actionLayout;
      }

      if(_.has(defaultDataService.params,'componentIndex')){

        result.params.componentIndex = defaultDataService.params.componentIndex;
      }

      if(_.has(defaultDataService.params,'sliderIndex')){

        result.params.sliderIndex = defaultDataService.params.sliderIndex;
      }

      return result;
    }
  },
  computed: {
    slidersParams:function(){
      return this.getScopeData("slidersParams",{});
    },
    model:function(){
      return this.getScopeData("model",{});
    },
    objects:function(){
      return this.getScopeData("objects",[]);
    },
    fields:function(){
      return this.getScopeData("fields",[]);
    },
    stateActions:function(){
      return this.getScopeData("stateActions",[]);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps",[]);
    },
    tabs:function(){
      return this.getScopeData("tabs",{});
    },
    sliders:function(){
      return this.getScopeData("sliders",{});
    },
    activetab:function(){
      return this.getScopeData("activetab",this.activeTabDefault);
    },
    opensliders:function(){
      return this.getScopeData("opensliders",[]);
    }
  },
  template: "#___idReference_-template"
});