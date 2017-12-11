define('vc-subwaies-popup', {
    template: "#template-subway-popup",
    data: function () {
        return {
            visible: false,
            activeLine: null,
            activeStations: []
        }
    },
    computed: {
        items: function () {
            return window.$viewData.dicts.subwaies;
        }
    },
    mounted: function () {
        var that = this;
        app.eventHub.$on('nav:changed', function (navId) {
            if (navId === 'subwaies') {
                that.visible = that.activeLine === null;
            } else {
                that.visible = false;
            }
        });
        app.eventHub.$on('subway:popup', function () {
            that.visible = true;
        });
    },
    methods: {
        groupStations: function (items) {
            var groupIdx = null;
            var arrItems = Object.values(items);
            var groupSize = 15;

            var groupedItems = {};
            for(var idx in arrItems) {
                groupIdx = Math.floor(parseInt(idx) / groupSize);
                if (! groupedItems.hasOwnProperty(groupIdx)) {
                    groupedItems[groupIdx] = [];
                }
                groupedItems[groupIdx].push(arrItems[idx]);
            }

            return groupedItems;
        },
        isActiveLine: function (line) {
            return this.activeLine === line;
        },
        isActiveStation: function (station) {
            return this.activeStations.indexOf(station) !== -1;
        },
        handleSelect: function (line, station) {
            if (this.activeLine !== line) {
                this.activeStations = [];
            }
            this.activeLine = line;

            var findPos = this.activeStations.indexOf(station);
            if (findPos !== -1) {
                this.activeStations.splice(findPos, 1);
            } else {
                this.activeStations.push(station);
            }
        },
        handleSelectAll: function (line) {
            this.activeLine = line;
            this.activeStations = Object.values(line.stations);
        },
        handleClearAll: function (line) {
            this.activeLine = null;
            this.activeStations = [];
        },
        handleConfirm: function () {
            var that = this;

            if (that.activeStations.length === 0) return;

            app.eventHub.$emit('subway:selected', {
                line: that.activeLine, stations: that.activeStations
            });
            
            this.visible = false;
        }
    },
    components: requires([
        'vc-subway-selector'
    ])
});

define('vc-subwaies', {
    template: "#template-subwaies",
    data: function () {
        return {
            line: null,
            stations: []
        };
    },
    mounted: function () {
        var that = this;
        app.eventHub.$on('subway:selected', function (selection) {
            that.line = selection.line;
            that.stations = selection.stations;

            that.handleValueChange();
        });
    },
    methods: {
        handleSelect: function () {
            app.eventHub.$emit('subway:popup');
        },
        handleValueChange: function () {
            this.$emit('input', {
                lineId: this.line.id,
                stationIds: this.stations.map(function (station) {
                    return station.id;
                })
            });
        }
    },
    components: requires([
        'vc-subwaies-popup'
    ])
});