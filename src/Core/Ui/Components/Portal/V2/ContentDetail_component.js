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
  methods: {},
  template: "#___tag_-template"
});
