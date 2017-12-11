define('vc-search', {
    template: "#template-search",
    props: ['value'],
    data: function () {
        return {
            q: this.value
        };
    },
    mounted: function () {
        this.init();
    },
    methods: {
        init: function () {
            var that = this;
            $.typeahead({
                input: '#q-input',
                minLength: 1,
                delay: 500,
                order: "desc",
                source: {
                    cities: {
                        display: "title",
                        /*
                        ajax: {
                            url: tt('', '/zh') + '/estate/autocomplete/',
                        },*/
                        data: window.$viewData.searchAutocompleteItems
                    }
                },
                template: function (query, item) {
                    return "{{title}} <small style='color:#999;float:right'>{{desc}}</small>";
                },
                callback: {
                    onClickAfter: function (node, a, item, event) {
                        that.q = item.title;
                        that.handleConfirm();
                    }
                }
            });
            $('#q-input').keydown(function (event) {
                if (event.keyCode == 13) {
                    that.handleConfirm();
                }
            });
        },
        handleConfirm: function () {
            if (this.q && $.trim(this.q) !== '') {
                this.q = this.q.toString()[0].toUpperCase() + this.q.toString().slice(1);
                this.$emit('input', this.q);
            }
        }
    }
});