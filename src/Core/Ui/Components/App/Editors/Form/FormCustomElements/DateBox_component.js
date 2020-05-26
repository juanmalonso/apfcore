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

    $('#field_calendar_' + this.$attrs.id).parent().calendar({
      type: 'datetime',
      ampm: false,
      formatter: {
          date: function (date, settings){
              if(date){
                return moment(date).format('DD-MMM-YYYY');
              }else{
                return '';
              }
          },
          time: function (date, settings, forCalendar) {
            if(date){
              return moment(date).format('HH:mm:ss');
            }else{
              return '';
            }
          }
      }
  });

  $('#field_' + this.$attrs.id).val(value);
  $('#field_calendar_' + this.$attrs.id).parent().calendar('set date',moment(value,'YYYY-MM-DD HH:mm:ss').toDate(),true,true);

  },
  methods: {
    getFieldValue: function () {
      
      var dateCalendar = moment($('#field_calendar_' + this.$attrs.id).parent().calendar('get date'),'YYYY-MM-DD HH:mm:ss').format();
      
      $('#field_' + this.$attrs.id).val(dateCalendar.replace("T"," ").substr(0, 19));

      return $('#field_' + this.$attrs.id).val();
    },
    doValidateField: function (){

      return this.validateDefinitions();
    }
  },
  computed: {
  },
  template: "#___tag_-template"
});
