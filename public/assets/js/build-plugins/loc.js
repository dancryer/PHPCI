var locPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-lines-chart',
    css: 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
    title: Lang.get('lines_of_code'),
    lastData: null,
    displayOnUpdate: false,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('phploc-lines', -1, {num_builds: 10, key: 'phploc'})

        $(window).on('phploc-lines', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function(data) {
            if (data.queryData.status > 1 && !self.rendered) {
                query();
            }
        });

        google.load("visualization", "1", {packages:["corechart"]});
    },

    render: function() {
        return $('<div id="phploc-lines"></div>').text(Lang.get('chart_display'));
    },

    onUpdate: function(e) {
        this.lastData = e.queryData;
        this.displayChart();
    },

    displayChart: function() {
        var builds = this.lastData;

        if (!builds || !builds.length) {
            $('#build-lines-chart').hide();
            return;
        }

        this.rendered = true;

        $('#phploc-lines').empty().animate({height: '275px'});

        var titles = [Lang.get('build'), Lang.get('lines'), Lang.get('comment_lines'), Lang.get('noncomment_lines'), Lang.get('logical_lines')];
        var data = [titles];
        for (var i in builds) {
            data.push(['#' + builds[i].build_id, parseInt(builds[i].meta_value.LOC), parseInt(builds[i].meta_value.CLOC), parseInt(builds[i].meta_value.NCLOC), parseInt(builds[i].meta_value.LLOC)]);
        }

        var data = google.visualization.arrayToDataTable(data);
        var options = {
            hAxis: {title: Lang.get('builds')},
            vAxis: {title: Lang.get('lines')},
            backgroundColor: { fill: 'transparent' },
            height: 275,
            legend: {position: 'bottom'}
        };

        $('#build-lines-chart').show();
        var chart = new google.visualization.LineChart(document.getElementById('phploc-lines'));
        chart.draw(data, options);
    }
});

ActiveBuild.registerPlugin(new locPlugin());
