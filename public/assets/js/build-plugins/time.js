var timePlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-time',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: null,
    box: true,

    init: function(){
        this._super();
    },

    render: function() {
        var created = '';
        var started = '';
        var finished = '';

        if (ActiveBuild.buildData.created) {
            created = dateFormat(ActiveBuild.buildData.created);
        }

        if (ActiveBuild.buildData.started) {
            started = dateFormat(ActiveBuild.buildData.started);
        }

        if (ActiveBuild.buildData.finished) {
            finished = dateFormat(ActiveBuild.buildData.finished);
        }

        return '<table class="table table-striped table-bordered">' +
            '<thead>' +
            '<tr>' +
                '<th style="width: 33.3%">'+Lang.get('build_created')+'</th>' +
                '<th style="width: 33.3%">'+Lang.get('build_started')+'</th>' +
                '<th style="width: 33.3%">'+Lang.get('build_finished')+'</th>' +
            '</tr>' +
            '</thead>' +
        '<tbody>' +
            '<tr>' +
            '<td id="created">' + created + '</td>' +
            '<td id="started">' + started + '</td>' +
            '<td id="finished">' + finished + '</td>' +
            '</tr>' +
        '</tbody>' +
        '</table>';
    },

    onUpdate: function(e) {
        var build = e.queryData;

        var created = '';
        var started = '';
        var finished = '';

        if (build.created) {
            created = dateFormat(build.created);
        }

        if (build.started) {
            started = dateFormat(build.started);
        }

        if (build.finished) {
            finished = dateFormat(build.finished);
        }

        $('#created').text(created);
        $('#started').text(started);
        $('#finished').text(finished);
    }
});

ActiveBuild.registerPlugin(new timePlugin());