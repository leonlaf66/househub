define('vc-nav', {
    template: "#template-nav",
    data: function () {
        return {
            current: 'areas',
            menuItems: {
                'areas': {
                    'text': tt('AREAS', '区域搜索'),
                    'icon': 'icon-area'
                },/*
                'subwaies': {
                    'text': tt('SUBWAY', '地铁搜索'),
                    'icon': 'icon-ditie'
                }*/
            },
        };
    },
    mounted: function () {
        var that = this;
        var currentId = 'areas';  // 默认切换到区域标签

        if (window.location.hash !== '') {
            currentId = window.location.hash.replace('#', '');
        }

        setTimeout(function () { // 等待加载完成
            that.switchMenu(currentId);
        }, 10);
    },
    methods: {
        switchMenu: function (menuId) {
            window.location.hash = '#' + menuId;
            this.current = menuId;
            app.eventHub.$emit('nav:changed', menuId);
            app.eventHub.$emit('nav:changed.' + menuId);
        },
    },
})
