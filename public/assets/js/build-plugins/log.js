var logPlugin = PHPCI.UiPlugin.extend({
    id: 'build-log',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: 'Build Log',

    init: function(){
        this._super();
    },

    render: function() {
        var container = $('<pre></pre>');
        container.css({height: '300px', 'overflow-y': 'auto'});
        container.html(PHPCI.buildData.log);

        return container;
    },

    onUpdate: function(e) {
        $('#build-log pre').html(e.queryData.log);
    }
});

PHPCI.registerPlugin(new logPlugin());