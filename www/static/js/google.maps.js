(function($){
      var currentClickMarker = null;
      
      google.maps.Map.prototype.markers = [];

      google.maps.Map.prototype.createLocation = function(b, k) {
         return new google.maps.LatLng(b, k);
      };

      google.maps.Map.prototype.createMarker = function(id, location, options) {
         var _default = {
              position: location,
              map: this,
              draggable:false,
              animation: google.maps.Animation.DROP,
              icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'
          };
          options = $.extend(_default, options);

          var marker = new google.maps.Marker(options);

          var transitLayer = new google.maps.TransitLayer();
          transitLayer.setMap(this);

          if(options.content) {
                marker.infowindow = new google.maps.InfoWindow({
                  content: options.content
                });

                google.maps.event.addListener(marker, "click", function(e){
                    if(currentClickMarker) {
                        currentClickMarker.infowindow.close();
                    }
                    currentClickMarker = marker;
                    marker.infowindow.open(options.map, marker);
                });
          }
          
          return this.markers[id] = marker;
      };

      google.maps.Map.prototype.getMarkerById = function(id) {
          return this.markers[id];
      };

      google.maps.Map.prototype.locationToMarkerById = function(id) {
          this.setCenter(this.markers[id]);
      };

      google.maps.Map.prototype.fitAllMarkers = function() {
          var map = this;
          var bounds = new google.maps.LatLngBounds();

          for(var key in map.markers) {
              bounds.extend(map.markers[key].position);
          }

          map.fitBounds(bounds);
      };

      google.maps.Map.prototype.search = function(address, callback) {
          var map = this;
          var geocoder = new google.maps.Geocoder();
          var bounds = new google.maps.LatLngBounds();

          geocoder.geocode({address: address}, function(results, status) {
              if(status == google.maps.GeocoderStatus.OK) {
                  bounds.extend(results[0].geometry.location);
                  map.setCenter(results[0].geometry.location);
                  // map.fitBounds(bounds);
                  if(callback) callback(results);
              }
          });
      };

      google.maps.Map.prototype.createControl = function(centerControlDiv) {
          this.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);
      };

      google.maps.Marker.prototype.showToCenter = function() {
          this.setCenter(this.position);
      };

      google.maps.Map.prototype.displayCityPolygon = function(coordinates) {
          var encodedpoints = [];
          var map = this;

          $.each(coordinates, function(idx, points){
            $.each(points, function (idx, item) {
              encodedpoints.push(
                 new google.maps.LatLng(item[1], item[0])
              );
            });
          });

          var polyOptions = {
                path: encodedpoints,
                strokeColor: "#f15044",
                strokeOpacity: 0.9,
                strokeWeight: 2,
                fillColor: "#ffffff",
                fillOpacity: 0.3
          }

          var it = new google.maps.Polygon(polyOptions);
          it.setMap(map);
      };

      google.maps.Map.prototype.createKmlLayer = function(kml) {
          var georssLayer = new google.maps.KmlLayer(kml);
          georssLayer.setMap(this);
      };
      
      $.fn.gmap3 = function(options) {
        var _default = {
           zoom: 8,
           mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        options = $.extend(_default, options);

        var map = $.data(this[0], 'googleMap');
        if (map) {
            return map;
        }

        map = new google.maps.Map(this[0], options);
        $.data(this[0], 'googleMap', map);

        $(this[0]).trigger('map:loaded', map);

        return map;
     };
})(jQuery);