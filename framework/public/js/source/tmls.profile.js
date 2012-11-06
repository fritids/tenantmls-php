$(document).ready(function() {

  $(".edit").click(function() {
    var li = $(this).parent('li');
    li.addClass('pedit');
    li.html('<div class="ajax-loader"></div>');
    $.post(TenantMLS.BASE_PATH+'/profiles/edit', {
      user_id : getCookie('tmls'),
      edit : $(this).attr('id')
    }, function(data) {
      li.html(data);
    });
  });
	var lon = 5;
    var lat = 40;
    var zoom = 5;
    var map, layer;
    map = new OpenLayers.Map( 'areamap',{
	    controls: [
	        new OpenLayers.Control.Navigation({'zoomWheelEnabled': false,defaultDblClick: function(event) {return;}}),
	        new OpenLayers.Control.ArgParser(),
	        new OpenLayers.Control.Attribution()
	    ]
	});
    layer = new OpenLayers.Layer.WMS( "OpenLayers WMS", 
            "http://vmap0.tiles.osgeo.org/wms/vmap0",
            {layers: 'basic'} );

    map.addLayer(layer);
    map.setCenter(new OpenLayers.LonLat(lon, lat), zoom);
    var featurecollection = {
      "type": "FeatureCollection", 
      "features": [
        {"geometry": {
            "type": "GeometryCollection", 
            "geometries": [ 
                {
                    "type": "Polygon", 
                    "coordinates": 
                        [[[11.0878902207, 45.1602390564], 
                          [14.931640625, 40.9228515625], 
                          [0.8251953125, 41.0986328125], 
                          [7.63671875, 48.96484375], 
                          [11.0878902207, 45.1602390564]]]
                }
            ]
        }, 
        "type": "Feature", 
        "properties": {}}
      ]
   };
   var geojson_format = new OpenLayers.Format.GeoJSON();
   var vector_layer = new OpenLayers.Layer.Vector(); 
   map.addLayer(vector_layer);
   vector_layer.addFeatures(geojson_format.read(featurecollection));
});