$(document).ready(function() {
	var lon = 5;
    var lat = 40;
    var zoom = 5;
    var map, layer;
    map = new OpenLayers.Map( 'searchmap',{
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
    
   var geojson_format = new OpenLayers.Format.GeoJSON();
   var vector_layer = new OpenLayers.Layer.Vector(); 
   map.addLayer(vector_layer);
})
