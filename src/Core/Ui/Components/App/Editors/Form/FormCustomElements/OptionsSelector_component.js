Vue.component("___tag_", {
  mixins: [nbsComponentMixin, nbsFieldComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;
    var value = this.$attrs.default;

    if(_.has(this.$attrs.data, this.$attrs.id)){
      
      value = _self.$attrs.data[this.$attrs.id];
    }

    $('.ui.checkbox').checkbox();
    $('.ui.radio.checkbox').checkbox();
    
    //FIELD
    if(this.$attrs.editAs == "FIELD"){

      $('#field_' + this.$attrs.id).val(value);
      $('#field_' + this.$attrs.id).dropdown('set selected',value);

    }else if(this.$attrs.editAs == "LIST"){

      if(_.isArray(value)){

        _.each(value, function(value, key, list){

          $("input[value='" + value + "']").parent().checkbox('check');
        });
      }
    }
  },
  methods: {
    getFieldValue: function () {

      var result = null;

      if(this.$attrs.editAs == "FIELD"){

        result = $('#field_' + this.$attrs.id).val();
  
      }else if(this.$attrs.editAs == "LIST"){
        result = [];

          $("input[name='" + this.$attrs.id + "']").each(function (){

            if($(this).parent().checkbox("is checked")){
  
              result.push($(this).val());
            }
          });
        
      }
      console.log(result);
      return result;
    },
    doValidateField: function (){

      return this.validateDefinitions();
    },
    isMultiple : function(){
      var result = false;

      if(this.$attrs.options.multiple != undefined){

        result = this.$attrs.options.multiple;
      }

      return result;
    }
  },
  computed: {
  },
  template: "#___tag_-template"
});
