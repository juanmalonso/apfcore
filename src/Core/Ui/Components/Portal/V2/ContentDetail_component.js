Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;

    $(document).ready(function(){
            	
      var mySwiper = new Swiper ('.swiper-container', {
        // Optional parameters
        loop: true,

        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
        },

        // Navigation arrows
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },

        // And if we need scrollbar
        /*scrollbar: {
          el: '.swiper-scrollbar',
        },*/
      })
      
    });
  },
  methods: {
    doReservar: function (){

      window.location = this.parseStringBlocks(this.reservaLinkMap, this.data);
    },
    hasCol3: function(){
      var result = false;

      _.each(this.data['variaciones'].tabla, function (value, key, list) {

        if(value.col3 > 0){

          result = true;
        }
      });

      return result;
    }
  },
  template: "#___tag_-template"
});
