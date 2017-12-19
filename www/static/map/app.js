var app = {
    eventHub: new Vue({}),
};

$('body').addClass(window.$viewData.type);

define('vc-loading', {
    template: "#template-loading",
    data: function () {
        return {
            visible: false
        }
    },
    mounted: function () {
        var that = this;
        app.eventHub.$on('loading:show', function () {
            that.visible = true;
        });
        app.eventHub.$on('loading:hide', function () {
            that.visible = false;
        });
    }
});

define('vc-return', {
    template: "#template-return",
    data: function () {
        return {
            href: '/house/'+window.$viewData.type+'/'
        };
    }
});

new Vue({
    el: "#map-app",
    data: function () {
        hub: new Vue({})
    },
    mounted: function () {
        window.onresize = function () {
            app.eventHub.$emit('window:resize', {
                width: $(window).width(),
                height: $(window).height()
            });
        };
    },
    components: requires(['vc-nav', 'vc-side-panel', 'vc-map', 'vc-return', 'vc-loading'])
});
