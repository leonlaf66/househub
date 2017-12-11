define('vc-side-panel', {
    template: "#template-side_panel",
    data: function () {
        return {
            mode: 'areas',
            q: null,
            schoolId: null,
            subway: {
                lineId: null,
                stationIds: []
            },
            filters: {
                prop_type: ['SF', 'CC', 'MF'],
                customs: {
                    price: [null, null],
                    square: [null, null]
                }
            }
        };
    },
    mounted: function () {
        var that = this;

        app.eventHub.$on('nav:changed', function (modeId) {
            that.mode = modeId;
        });

        this.$refs['filters'].$on('input', function () {
            that.search();
        });
    },
    methods: {
        search: function () {
            var that = this;
            var params = {};
            if (this.mode === 'areas' && this.q) {
                params = {
                    mode: 'areas',
                    mode_val: this.q
                };
            } else if (this.mode === 'schools' && this.schoolId) {
                params = {
                    mode: 'schools',
                    mode_val: this.schoolId
                };
            } else if (this.mode === 'subwaies' && this.subway.stationIds.length > 0) {
                params = {
                    mode: 'subwaies',
                    mode_val: this.subway
                };
            }
            if (! params.mode_val) return; // 必须有指定范围

            // 只提交有值的fitlers
            params.filters = {};
            for (var filterId in this.filters) {
                if (filterId === 'customs') {
                    for (var fId in this.filters.customs) {
                        if (this.filters.customs[fId][0] && this.filters.customs[fId][1]) {
                            this.filters[fId] = '@' + this.filters.customs[fId][0] + '~' + this.filters.customs[fId][1]; // 这@，以代表后面需要转换单位
                        }
                    }
                } else if (this.filters[filterId]) {
                    params.filters[filterId] = this.filters[filterId];
                }
            }

            // 加入验证
            params._csrf = document.getElementById('csrf-token').content;

            app.eventHub.$emit('loading:show');

            var url = tt('/map/house/'+window.$viewData.type+'/search/', '/zh/map/house/'+window.$viewData.type+'/search/');
            $.post(url, params, function (result) {
                that.onSearched(result);
            });
        },
        onSearched: function (result) {
            app.eventHub.$emit('items:changed', result);
        },
        isInMode: function (mode) {
            return this.mode === mode;
        },
        switchMode: function (mode) {
            this.mode = mode;
        },
    },
    watch: {
        'mode': function () {
            this.search();
        },
        'q': function () {
            this.search();
        },
        'schoolId': function () {
            this.search();
        },
        'subway': function () {
            this.search();
        }
    },
    components: requires([
        'vc-nav',
        'vc-search',
        'vc-subwaies',
        'vc-schools',
        'vc-filters'
    ])
});
