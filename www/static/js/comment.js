(function($) {
$.fn.commentServcie = function(options) {     
    var defaults = {
        loadUrl: '',
        submitUrl: '',
        statusUpdateUrl: '',
        totalElement: null,
        itemsElement: null,
        itemSelector: '.comment-item',
        onSubmitBefore: function () {}
    };
    var opts = $.extend(defaults, options);
    var commentElement = this;

    var comment = function(){
        this.init = function() {
            var that = this;
            commentElement.on('refresh', function(){
                opts.totalElement.text(that.getItemCount());
                that.clearForm();

            }).on('submitBefore', function(){
                commentElement.addClass('processing');
                opts.onSubmitBefore();

            }).on('submitAfter', function(){
                that.updateStatus(function(){
                    commentElement.removeClass('processing');
                    opts.onSubmitAfter();

                    that.refresh();
                });
            });
            commentElement.find('button.save').click(function(){
                return that.submitForm();
            });
            commentElement.find('button.cancel').click(function(){
                that.clearForm();
            });
            this.refresh();
        },
        this.refresh = function(fn) {
            var that = this;
            opts.itemsElement.load(opts.loadUrl, function(){
                commentElement.trigger('refresh');
            });
        };
        this.submitForm = function() {
            var data = {};
            var status = true;

            commentElement.trigger('processBeggin');
            commentElement.find('form [name]').each(function(){
                var input = $(this);
                if($.trim(input.val()) === '') {
                    input.focus();
                    status = false;
                }
                else {
                    data[input.attr('name')] = input.val();
                }
            });
            if(! status) return false;
            
            commentElement.trigger('submitBefore');
            $.post(opts.submitUrl, data, function(){
                commentElement.trigger('submitAfter');
            });
        };
        this.updateStatus = function(fn){
            var url = opts.statusUpdateUrl;
            url = url.replace('var.rating', commentElement.find('form [name=rating]').val())
                .replace('var.total', parseInt(opts.totalElement.text())+1);
            $.post(url, fn);
        };
        this.getItemCount = function() {
            return opts.itemsElement.find(opts.itemSelector).length;
        };
        this.clearForm = function() {
            var that = this;
            commentElement.find('form [name=rating]').val(1);
        };
        this.init();
    };

    return new comment();
};     
})(jQuery);