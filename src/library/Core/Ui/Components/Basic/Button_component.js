Vue.component('___tag_', {
    mixins: [nbsComponentMixin],
		data: function () {
			return ___jsdata_
		},
		methods: {
			doClick: function(){

				var serviceOptions = {
					data:{"key1":"value1", "key2":"value2"},
					successcbk:this.doTestSuccess,
					emitter:$(event.target),
					loading:true				
				}

				this.loadService("test", serviceOptions);
				
			},
			doTestSuccess: function(response, data){

				var self = this;
					
				_.each(data, function (value, key){
					
					Vue.set(self,key,value);
				});

			},
			doData: function(event){
				
				var serviceOptions = {
					data:{"key1":"value1", "key2":"value2"},
					emitter:$(event.target),
					loading:true				
				}

				this.reloadData(serviceOptions);
				
			}
		},
		template: '#___tag_-template'
});