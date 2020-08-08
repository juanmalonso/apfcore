Vue.component("___tag_", {
  mixins: [nbsComponentMixin],
  data: function () {
    return ___jsdata_;
  },
  mounted: function () {
    var _self = this;

    _.each(this.filters, function (value, key, list) {

      var selected  = [];

      if(_.has(value, "selected")){

        selected    = value.selected;
      }

      if(value.type == "dropdown"){

        _self.setFilterDropdown(value.id, value.label, value.data, selected);
      }

      if(value.type == "togglebuttons"){

        _self.setFilterToggleButton(value.id, value.data, selected);
      }
      
    });

  },
  methods: {
    doFilter: function(){
      var _self         = this;
      var namedParams   = [];
      var filtersKeys   = ["keyword"];

      //SEARCH
      var keyword           = $("#keyword").val();
      
      if(keyword == ""){

        keyword         = "*"
      }
      
      namedParams.push({"key":"keyword", "values":[keyword]});

      _.each(this.filters, function (value, key, list) {

        filtersKeys.push(value.id);

        if(value.type == "dropdown"){

          filterValue = _self.getDropdownFilterValue(value.id);
        }

        if(value.type == "togglebuttons"){

          filterValue = _self.getTogglebuttonsFilterValue(value.id);
        }

        console.log("FILTER VALUE LENGHT", value.id, filterValue.length);

        if(filterValue.length > 0 && value.id != 'keyword'){

          console.log("ALL?", _.indexOf(filterValue, "all"));

          if(_.indexOf(filterValue, "all") == -1){

            namedParams.push({"key":value.id, "values":filterValue});
          }
        }
      });

      console.log("DO FILTER", filtersKeys, namedParams);
      console.log(this.doMappingFilterUrl(filtersKeys, namedParams));
      window.location = this.doMappingFilterUrl(filtersKeys, namedParams);
    },
    doMappingFilterUrl: function (filtersKeys, filtersParams){
      var actualUrl           = window.location.href;

      _.each(filtersKeys, function (value, key, list) {

        var pattern         = new RegExp('(.*)(' + value + ':[\*a-zA-Z0-9,_\-]+\/?)(.*)','g');
        
        //console.log("VALUE", value);
        //console.log("PATTERN", pattern);

        //console.log("ACTUAL URL A", actualUrl);

        actualUrl = actualUrl.replace(pattern, function (match, p1, p2, p3) {          

          result    = p1 + p3;
          
          return result;
        });

        //console.log("ACTUAL URL B", actualUrl);
      });
      
      _.each(filtersParams, function (value, key, list) {
        
        if(actualUrl.substr(actualUrl.length - 1) == "/"){

          actualUrl = actualUrl + value.key + ":" + value.values.join(",");
        }else{

          actualUrl = actualUrl + "/" + value.key + ":" + value.values.join(",");
        }
      });

      //console.log("FINAL URL", actualUrl);

      return actualUrl;
    },
    setFilterDropdown: function (id, placeholder, values, selectedItems) {
      var _self = this;
      var dropdownValues = [];
      
      $('#filter_' + id).empty();

      _.each(values, function (value, key, list) {

        var tempItem = { "name": value.label, "value": value.value };

        if(_.indexOf(selectedItems, value.value) !== -1){

          tempItem['selected'] = true;
        }

        dropdownValues.push(tempItem);

        if(_.has(tempItem,"selected")){

          $('#filter_' + id).append( '<option value="'+value.value+'" selected="true">'+value.label+'</option>' );
        }else{

          $('#filter_' + id).append( '<option value="'+value.value+'">'+value.label+'</option>' );
        }
      });

      if(dropdownValues.length == 0 && selectedItems.length > 0){

        _.each(selectedItems, function (value, key, list) {

          $('#filter_' + id).append( '<option value="'+value+'" selected="selected">'+value+'</option>' );
        });
      }
      
      $('#filter_' + id).dropdown({
        placeholder: placeholder,
        values: dropdownValues
      });

      /*$('#field_' + _self.$attrs.id).dropdown("setup menu", {

        values: dropdownValues
      });*/

      //$('#field_' + _self.$attrs.id).dropdown("set text","");

      $('#filter_' + id).dropdown("set selected", selectedItems);
      
      $('#filter_' + id).val(selectedItems);

      /*
      $('#field_' + _self.$attrs.id).on('change', function (event){

        _self.$emit('do-change', {"params":_self.getFieldValue(), "component": _self});
      });
      */
    },
    setFilterToggleButton: function (id, values, selectedItems){
      
      console.log("setFilterToggleButton", id, values, selectedItems);

      _.each(values, function (value, key, list) {

        $('#filter_' + id + '_' + value.value).removeClass("green");

        if(_.indexOf(selectedItems, value.value) !== -1){

          $('#filter_' + id + '_' + value.value).addClass("green");
        }
      });
      
    },
    doToggleFilter: function (filter, pvalue){
      
      console.log("doToogleFilter", filter, pvalue);
      
      var selected  = [];

      // SE RECUPERA VALOR ACTUAL
      if(_.has(filter, "selected")){

        selected    = filter.selected;
      }

      console.log("doToogleFilter TOGGLE?", _.indexOf(selected, pvalue));

      if(_.indexOf(selected, pvalue) == -1){

        if(filter.multiple){

          selected.push(pvalue);
        }else{
  
          selected = [pvalue];
        }
      }else{

        var newSelected = [];

        _.each(selected, function (value, key, list) {

          console.log(value, pvalue, value != pvalue);

          if(value != pvalue){

            newSelected.push(value);
          }

        });

        selected = newSelected;
      }

      console.log("doToogleFilter SELECTED", selected);
      
      _.each(filter.data, function (value, key, list) {
        
        $('#filter_' + filter.id + '_' + value.value).removeClass("green");
        
        if(_.indexOf(selected, value.value) !== -1){

          $('#filter_' + filter.id + '_' + value.value).addClass("green");
        }

      });

      //this.filters[filter.id].selected = selected;

      this.$set(this.filters[filter.id], "selected", selected);
      
      console.log("TOGGLE FILTER", filter, pvalue);
      
      this.doFilter();
    },
    getTogglebuttonsFilterValue: function (id) {
      var result = this.filters[id].selected;
      console.log("VALUE ->", result);
      if(_.isString(result)){

        result = [result];
      }

      if(_.isNull(result)){

        result = [];
      }

      if(_.isArray(result) && result.length == 1 && result[0] == ""){

        result = [];
      }

      console.log("FILTER VALUE", result);
      return result;
    },
    getDropdownFilterValue: function (id) {
      var result = $('#filter_' + id).val();

      if(_.isString(result)){

        result = [result];
      }

      if(_.isNull(result)){

        result = [];
      }

      console.log("FILTER VALUE", result);
      return result;
    },
    hasDropdownFilters: function(){
      var result = false;

      _.each(this.filters, function (value, key, list) {

        if(value.type == "dropdown"){

          result = true;
        }
      });

      return result;
    }
  },
  computed: {
    

  },
  template: "#___tag_-template"
});
