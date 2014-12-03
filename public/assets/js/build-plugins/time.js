var timePlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-time',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: null,
    box: true,

    init: function(){
        this._super();
    },

    render: function() {
        var created = new Date(ActiveBuild.buildData.created);

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
            '<td id="created">' + created.format('mmm d yyyy, H:MM') + '</td>' +
            '<td id="started">' + ActiveBuild.buildData.started + '</td>' +
            '<td id="finished">' + ActiveBuild.buildData.finished + '</td>' +
            '</tr>' +
        '</tbody>' +
        '</table>';
    },

    onUpdate: function(e) {
        var build = e.queryData;

        var created = new Date(build.created);

        var started = '';
        if (build.started) {
            var started = new Date(build.started);
            started = started.format('mmm d yyyy, H:MM');
        }

        var finished = '';
        if (build.finished) {
            var finished = new Date(build.finished);
            finished = finished.format('mmm d yyyy, H:MM');
        }
        $('#created').text(created.format('mmm d yyyy, H:MM'));
        $('#started').text(started);
        $('#finished').text(finished);
    }
});

ActiveBuild.registerPlugin(new timePlugin());