/**
 参考文档:
 自定义坐标：http://www.cnblogs.com/yincheng/p/google-map.html
 */
var gmap = {};

gmap.cache = {};

gmap.helper = {
    //构造markers
    makeMarkers: function (items, callback) {
        var markers = [];

        return items.map(function (data) {
            var marker = new HouseMarker(new google.maps.LatLng(data.latitude, data.longitude), {
                data: data,
                marker_id: data.id
            });

            if (typeof callback === 'function') {
                callback(marker);
            }

            return marker;
        });
    },

    //构造cluster样式集
    makeClusterStyles: function () {
        return [{
            url: '/static/img/map/cluster/m1.png',
            height: 53,
            width: 53,
            textColor: '#ffffff'
          }, {
            url: '/static/img/map/cluster/m2.png',
            height: 56,
            width: 56,
            textColor: '#ffffff'
          }, {
            url: '/static/img/map/cluster/m3.png',
            height: 66,
            width: 66,
            textColor: '#ffffff'
          },
          {
            url: '/static/img/map/cluster/m4.png',
            height: 78,
            width: 78,
            textColor: '#ffffff'
          },
          {
            url: '/static/img/map/cluster/m5.png',
            height: 90,
            width: 90,
            textColor: '#ffffffs'
          }
        ];
    }
};


gmap.$ = function (map) {
    var plugins = {};

    //搜索MA中的城市或地址
    plugins.searchAddress = function (address) {
        var geocoder = new google.maps.Geocoder();

        geocoder.geocode({address: address+' MA USA'}, function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
            }
        });

        return this;
    };

    // 调整边界视角
    plugins.fitBounds = function(positions) {
        var bounds = new google.maps.LatLngBounds();

        for(var v in positions) {
            bounds.extend(positions[v]);
        }

        map.fitBounds(bounds);

        return this;
    };

    //构造边界
    plugins.makePolygon = function(coordinates) {
        var triangleCoords = coordinates.map(function (item) {
            return {
                'lat': item[1],
                'lng': item[0]
            };
        });

        var polyOptions = {
            paths: triangleCoords,
            strokeColor: '#f15044',
            strokeOpacity: 0.9,
            strokeWeight: 3,
            fillColor: '#ffffff',
            fillOpacity: 0
        };

        var it = new google.maps.Polygon(polyOptions);
        it.setMap(map);

        gmap.cache.$polygon= it;

        return this;
    };

    plugins.clearPolygon = function () {
        if (gmap.cache.$polygon) {
            gmap.cache.$polygon.setMap(null);
        }
    };

    return plugins;
};


