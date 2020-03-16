Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function() {
    return ___jsdata_;
  },
  mounted: function() {
    var _self = this;

    console.log(_.size(this.linksFields), this.linksFields);
  },
  methods: {},
  computed: {
    colsNum: function() {
      var result = 1; //numrows column

      result += _.size(this.tableFields);

      if (_.size(this.linksFields) > 0) {
        result += 1;
      }

      if (_.size(this.actionsFields) > 0) {
        result += 1;
      }

      return result;
    }
  },
  template: "#___tag_-template"
});
