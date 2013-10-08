var timePlugin = PHPCI.UiPlugin.extend({
    id: 'build-time',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: null,
    box: true,

    init: function(){
        this._super();
    },

    render: function() {
        return '<table class="table table-striped table-bordered">' +
            '<thead>' +
            '<tr>' +
                '<th style="width: 33.3%">Build Created</th>' +
                '<th style="width: 33.3%">Build Started</th>' +
                '<th style="width: 33.3%">Build Finished</th>' +
            '</tr>' +
            '</thead>' +
        '<tbody>' +
            '<tr>' +
            '<td id="created">' + PHPCI.buildData.created + '</td>' +
            '<td id="started">' + PHPCI.buildData.started + '</td>' +
            '<td id="finished">' + PHPCI.buildData.finished + '</td>' +
            '</tr>' +
        '</tbody>' +
        '</table>';
    },

    onUpdate: function(e) {
        var build = e.queryData;

        $('#created').text(build.created);
        $('#started').text(build.started);
        $('#finished').text(build.finished);
    }
});

PHPCI.registerPlugin(new timePlugin());