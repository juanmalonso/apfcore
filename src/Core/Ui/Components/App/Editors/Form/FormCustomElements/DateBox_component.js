Vue.component("___idReference_", {
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
    
    console.log("DATE BOX INITIAL VALUE", this.$attrs.id, value);
    
    $('#field_' + this.$attrs.id).val(value);
    $('#field_' + this.$attrs.id).flatpickr({
      enableTime: true,
      dateFormat: "Y-m-d H:i",
      defaultDate: value
    });

    //$('#field_calendar_' + this.$attrs.id).parent().calendar('set date',moment(value,'YYYY-MM-DD HH:mm:ss').toDate(),true,true);

  },
  methods: {
    getFieldValue: function () {
      /*
      var dateCalendar = moment($('#field_calendar_' + this.$attrs.id).parent().calendar('get date'),'YYYY-MM-DD HH:mm:ss').format();
      
      $('#field_' + this.$attrs.id).val(dateCalendar.replace("T"," ").substr(0, 19));
      */
      console.log("DATE BOX", this.$attrs.id, $('#field_' + this.$attrs.id).val());

      return $('#field_' + this.$attrs.id).val();
    },
    doValidateField: function (){

      return this.validateDefinitions();
    }
  },
  computed: {
  },
  template: "#___idReference_-template"
});
