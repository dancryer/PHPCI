var timePlugin = PHPCI.UiPlugin.extend({
    id: 'build-time',
    css: 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
    title: null,
    box: true,

    init: function(){
        this._super();
    },

    render: function() {
        return '<table class="table table-striped table-bordered">' +
        '<tbody>' +
            '<tr>' +
            '<th>Build Created</th>' +'<td id="created">' + PHPCI.buildData.created + '</td>' +
            '</tr>' +
            
            '<tr>' +
            '<th>Build Started</th>' + '<td id="started">' + PHPCI.buildData.started + '</td>' +
            '</tr>' +

            '<tr>' +
            '<th>Build Finished</th>' + '<td id="finished">' + PHPCI.buildData.finished + '</td>' +
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
