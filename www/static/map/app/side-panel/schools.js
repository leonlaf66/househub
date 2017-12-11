define('vc-schools-popup', {
    template: "#template-school-popup",
    data: function () {
        return {
            visible: false,
            activeSchool: null,
            $list: null
        }
    },
    computed: {
        items: function () {
            return window.$viewData.dicts.schools;
        }
    },
    created: function () {
        var that = this;
        app.eventHub.$on('nav:changed', function (navId) {
            if (navId === 'schools') {
                if (that.activeSchool === null) {
                    that.visible = true;
                }
            } else {
                that.visible = false;
            }
        })
        app.eventHub.$on('schools:popup', function () {
            that.visible = true;
        });
        app.eventHub.$on('window.resize', function (size) {
            that.resize(size);
        });
    },
    updated: function () {
        this.$list = $(this.$el).find('.list');

        this.resize({
            width: $(window).width(),
            height: $(window).height()
        });
    },
    methods: {
        getIsActive: function (id) {
            return this.activeSchool && this.activeSchool.id == id;
        },
        resize: function (size) {
            if (this.$list && this.$list.offset()) {
                this.$list.height((size.height - this.$list.offset().top) + 'px');
            }
        },
        handleChanged: function (school) {
            this.activeSchool = school;
            this.visible = false;
            app.eventHub.$emit('school:changed', school);
        }
    }
});

define('vc-schools', {
    template: "#template-schools",
    data: function () {
        return {
            school: null
        }
    },
    mounted: function () {
        var that = this;
        app.eventHub.$on('school:changed', function (school) {
            that.school = school;
            that.$emit('input', school.id);
        });
    },
    methods: {
        handleSelect: function () {
            app.eventHub.$emit('schools:popup');
        }
    },
    components: requires([
        'vc-schools-popup'
    ])
});