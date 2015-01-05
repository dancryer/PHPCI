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
            created = moment(ActiveBuild.buildData.created).format('ll LT');
        }

        if (ActiveBuild.buildData.started) {
            started = moment(ActiveBuild.buildData.started).format('ll LT');
        }

        if (ActiveBuild.buildData.finished) {
            finished = moment(ActiveBuild.buildData.finished).format('ll LT');
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
            created = moment(build.created).format('ll LT');
        }

        if (build.started) {
            started = moment(build.started).format('ll LT');
        }

        if (build.finished) {
            finished = moment(build.finished).format('ll LT');
        }

        $('#created').text(created);
        $('#started').text(started);
        $('#finished').text(finished);
    }
});

ActiveBuild.registerPlugin(new timePlugin());