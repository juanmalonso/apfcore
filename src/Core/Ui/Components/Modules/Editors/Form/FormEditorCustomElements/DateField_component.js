Vue.component("___idReference_", {
  mixins: [nbsComponentMixin, nbsModulesComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var self = this;

  },
  methods: {
    initField: function(){

      //
    },
    setFieldValue: function (value) { 
      var self = this;
      var flatpickrOptions                    = self.getDatePickrOptions(); 

      if(value != ""){

        flatpickrOptions.defaultDate            = value;
      }

      $(this.$refs.realField).val(value);
      $(this.$refs.realField).flatpickr(flatpickrOptions);

    },
    getFieldValue: function () {

      return $(this.$refs.realField).val();
    },
    doListenerEmit: function(value){
      var self                                = this;
      var flatpickrOptions                    = self.getDatePickrOptions();

      flatpickrOptions.minDate                = moment(value[0]).format('YYYY-MM-DD');
      flatpickrOptions.defaultDate            = moment(value[0]).format('YYYY-MM-DD');

      console.log("EMIT", value[0]);
      
      $(this.$refs.realField).flatpickr(flatpickrOptions);
    },
    getDatePickrOptions: function(){
      var self                                = this;
      var flatpickrOptions                    = {};
      
      flatpickrOptions.onChange               = function(selectedDates, dateStr, instance) {
        
        console.log("CHANGE", self.getTypeOption("listeners"));

        if(self.getTypeOption("listeners") != undefined){

          _.each(self.getTypeOption("listeners"), function (value, key, list) {
  
            var listenerReference = self.referenceName.replace(self.fieldIndex, value);
  
            self.getByReferenceName(listenerReference).doListenerEmit(selectedDates);
          });
        }
      }
      
      //ALLOW INPUT
      flatpickrOptions.allowInput             = false;
      if(self.getTypeOption("allowInput") != undefined){

        flatpickrOptions.allowInput           = self.getTypeOption("allowInput");
      }

      //ENABLE TIME
      flatpickrOptions.enableTime             = true;
      if(self.getTypeOption("enableTime") != undefined){

        flatpickrOptions.enableTime           = self.getTypeOption("enableTime");
      }

      //DATE FORMAT
      if(self.getTypeOption("dateFormat") != undefined){

        flatpickrOptions.dateFormat           = self.getTypeOption("dateFormat");
      }

      //MAX DATE
      if(self.getTypeOption("maxDate") != undefined){

        flatpickrOptions.maxDate              = self.parseStringBlock(self.getTypeOption("maxDate"),{});
      }

      //MIN DATE
      if(self.getTypeOption("minDate") != undefined){
        
        flatpickrOptions.minDate              = self.parseStringBlock(self.getTypeOption("minDate"),{});
      }

      //DEFAULT VALUE
      if(self.getTypeOption("defaultDate") != undefined){

        flatpickrOptions.defaultDate          = self.parseStringBlock(self.getTypeOption("defaultDate"),{});
      }

      return flatpickrOptions;
    }
  },
  computed: {
    forForm:function(){
      return this.getScopeData("forForm", this.$attrs.forForm);
    },
    field:function(){
      return this.getScopeData("field", this.$attrs.field);
    },
    fieldIndex:function(){
      return this.getScopeData("fieldIndex", this.$attrs.fieldIndex);
    },
    data:function(){
      return this.getScopeData("data", this.$attrs.data);
    },
    urlMaps:function(){
      return this.getScopeData("urlMaps", this.$attrs.urlMaps);
    }
  },
  template: "#___idReference_-template"
});
