var houseDetail = {
    instance: null,
    createVue: function (mlsId) {
        this.instance = null;
        this.instance = new Vue({
            el: '#house-detail',
            template: '<div class="house-detail-popup" style="min-height:100px;min-width:400px;">'+
                            '<div v-if="!data">'+tt('Loading...', '载入中...')+'</div>'+
                            '<template v-if="data">'+
                                '<a :href="houseUrl" target="_blank">'+
                                    '<div class="col" style="width:120px;height:100px">'+
                                        '<div class="image event-none" :style="{backgroundImage: imageUrl}"></div>'+
                                    '</div>'+
                                    '<div class="col col2">'+
                                        '<template v-if="isRental">'+
                                            '<div class="name event-none" style="margin-bottom:12px">{{ data.location[\'value\'] }}</div>'+
                                        '</template>'+
                                        '<template v-else>'+
                                            '<div class="name event-none">{{ data.name[\'value\'] }}</div>'+
                                            '<div class="location event-none">{{ data.location[\'value\'] }}</div>'+
                                        '</template>'+
                                        '<ul class="fields event-none">'+
                                            '<li class="field"><label>{{ data.no_bedrooms[\'title\'] }}</label><span>{{ data.no_bedrooms[\'value\'] }}</span></li>'+
                                            '<li class="separate"></li>'+
                                            '<li class="field"><label>{{ data.no_full_baths[\'title\'] }}</label><span>{{ data.no_full_baths[\'value\'] }}</span></li>'+
                                            '<li class="field"><label>{{ data.no_half_baths[\'title\'] }}</label><span>{{ data.no_half_baths[\'value\'] }}</span></li>'+
                                            '<li class="separate"></li>'+
                                            '<li class="field"><label>{{ data.square_feet[\'title\'] }}</label><span>{{ data.square_feet[\'value\'] }} {{ data.square_feet[\'suffix\'] }}</span></li>'+
                                        '</ul>'+
                                        '<ul class="tags event-none">'+
                                            '<li class="tag">{{ data.status_name[\'value\'] }}</li>'+
                                            '<li class="tag" v-if="data.no_list_days[\'value\']">{{ tt(data.no_list_days[\'value\']+" days on market", "已上市"+data.no_list_days[\'value\']+"天") }}</li>'+
                                        '</ul>'+
                                    '</div>'+
                                '</a>'+
                            '</template>'+
                      '</div>',
            data: function () {
                return {
                    data: null
                };
            },
            computed: {
                imageUrl: function () {
                    return 'url('+this.data.image_url+')';
                },
                houseUrl: function () {
                    return '/'+window.$viewData.type+'/'+this.data.id+'/';
                },
                isRental: function () {
                    return window.$viewData.type === 'lease';
                }
            },
            mounted: function () {
                this.load();
            },
            methods: {
                load: function () {
                    var that = this;
                    var url = tt('/map/house/'+mlsId+'/', '/zh/map/house/'+mlsId+'/');
                    $.get(url, function (data) {
                        that.data = data;
                    });
                }
            }
        });
    }
}