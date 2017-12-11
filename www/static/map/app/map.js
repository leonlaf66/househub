define('vc-map', {
    template: "#template-map",
    data: function () {
        return {
            instance: null,
            cluster: null,
            markers: [],
            mapedMarkers: {},
            propTypeNames: {
                '2': tt('Single Family', '单家庭'),
                '3': tt('Multi Family', '多家庭'),
                '4': tt('Condominium', '公寓'),
                '5': tt('Commercial', '商业用房'),
                '6': tt('Business Opportunity', '营业用房'),
                '7': tt('Land', '土地')
            },
            infoWindow: null
        };
    },
    mounted: function () {
        var that = this;

        this.create();
        this.search('BOST');

        app.eventHub.$on('items:changed', function (results) {
            that.instance.setZoom(12);

            // 清除边界选区
            gmap.$(that.instance).clearPolygon();

            if (results.items.length === 0) {
                app.eventHub.$emit('loading:hide');
                that.reset();
                return;
            } else {
                that.setMarkers(results.items);
            }

            if (results.cityPolygons.length > 0) {
                that.makePolygon(results.cityPolygons);
            } else {
                if (results.city) {
                    that.search(results.city);
                } else {
                     var positions = that.markers.map(function (marker) {
                        // return marker.position;
                        return marker.latlng;
                     });
                     gmap.$(that.instance).fitBounds(positions);
                }
            }

            app.eventHub.$emit('loading:hide');
        });

        // 委托所有maker:click事件，弹框显示详细信息
        $('#map').on('click', '.gmap-html-marker', function (event) {
            var mlsId = $(this).data('marker_id');
            that.popupDetailWindow(mlsId);
            return true;
        });
    },
    methods: {
        create: function () {
            this.instance = new google.maps.Map(this.$el, {
                zoom: 12,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControlOptions: {
                    position: google.maps.ControlPosition.TOP_LEFT
                }
            });
            var transitLayer = new google.maps.TransitLayer();
            transitLayer.setMap(this.instance);

            var mapStyles = [
                {
                  featureType: 'transit',
                  elementType: 'geometry',
                  stylers: [{weight: '5px'}]
                }
            ];

            this.instance.setOptions({styles: mapStyles});

            // 构造cluster
            this.cluster = new MarkerClusterer(this.instance, [], {
                gridSize: 80, // The grid size of a cluster in pixels.
                maxZoom: 16, // The maximum zoom level that a marker can be part of a cluster.
                minimumClusterSize: 3,
                imagePath: '/static/img/map/cluster/m',
                styles: gmap.helper.makeClusterStyles(),
                averageCenter: true,
            });

            return this.instance;
        },
        search: function (address) {
            gmap.$(this.instance).searchAddress(address);
        },
        setMarkers: function (items) { ////https://github.com/googlemaps/js-marker-clusterer
            var that = this;
            var itemParts;

            // 重置
            this.reset();

            // 构造markers
            this.mapedMarkers = {};
            items = items.map(function (itemStr) {
                itemParts = itemStr.split('|');
                return {
                    id: itemParts[0],
                    latitude: itemParts[1],
                    longitude: itemParts[2],
                    list_price: itemParts[3],
                    prop_type_name: that.propTypeNames[itemParts[4]]
                };
            });

            // 重置聚合
            this.cluster.resetViewport();

            this.markers = gmap.helper.makeMarkers(items, function (marker) {
                // 映射到mapedMarkers
                that.mapedMarkers[marker.args.marker_id] = marker;

                // 添加至cluster
                that.cluster.addMarker(marker, true);
            });

            // 首次需要手动绘制
            this.cluster.redraw();

            return this.cluster;
        },
        updateMarkders: function (markers) {
            this.markers = gmap.helper.makeMarkers(markers);
        },
        reset: function () {
            // 清除markers
            for (var i = 0; i < this.markers.length; i++ ) {
                this.markers[i].setMap(null);
            }
            this.markers.length = 0;

            // 清除clusters
            if (this.cluster) {
                this.cluster.clearMarkers();
            }
        },
        makePolygon: function (polygonBlocks) {
            var gmapUtil = gmap.$(this.instance);

            var bounds = new google.maps.LatLngBounds();
            for (var i = 0; i < polygonBlocks.length; i ++) {
                for (j in polygonBlocks[i]) {
                    bounds.extend(new google.maps.LatLng(polygonBlocks[i][j][1], polygonBlocks[i][j][0]));
                }
                gmapUtil.makePolygon(polygonBlocks[i]);
            }
            this.instance.fitBounds(bounds);
        },
        popupDetailWindow: function (mlsId) {
            var marker = this.mapedMarkers[mlsId];

            if (! this.infoWindow) {
                this.infoWindow = new google.maps.InfoWindow({
                    content: null,
                    pixelOffset: new google.maps.Size(0, -34),
                    height: 124
                });

                google.maps.event.addListener(this.infoWindow, 'domready', function(){
                    window.houseDetail.createVue(this.anchor.args.marker_id);
                });
            }

            this.infoWindow.setContent('<div id="house-detail"></div>');
            this.infoWindow.open(this.instance, marker);
        }
    }
});
