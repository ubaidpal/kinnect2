define(["jquery","backbone","models/Model"],
  function($, Backbone, Model) {
    // Creates a new Backbone Collection class object
    var Collection = Backbone.Collection.extend({
      // Tells the Backbone Collection that all of it's models will be of type Model (listed up top as a dependency)
        model: Model,

        sync: function(method, collection, options){
            if(method == "read"){
                this.traversal(collection, this.mydata);
            }
            alert(method);
        },

        traversal: function(collection, mydata){
            $.each(mydata, function(index, data){
                collection.push(data);
            });
        }
  });

    return Collection;
  });