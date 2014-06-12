var locPlugin = PHPCI.UiPlugin.extend({
    id: 'build-lines-chart',
    css: 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
    title: 'Lines of Code',
    lastData: null,
    displayOnUpdate: false,
    rendered: false,

    register: function() {
        var self = this;
        var query = PHPCI.registerQuery('phploc-lines', -1, {num_builds: 10, key: 'phploc'})

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
        return $('<div id="phploc-lines"></div>').text('This chart will display once the build has completed.');
    },

    onUpdate: function(e) {
        this.lastData = e.queryData;
        this.displayChart();
    },

    displayChart: function() {
        var builds = this.lastData;

        if (!builds || !builds.length) {
            return;
        }

        this.rendered = true;

        $('#phploc-lines').empty().animate({height: '275px'});

        var titles = ['Build', 'Lines', 'Comment Lines', 'Non-Comment Lines', 'Logical Lines'];
        var data = [titles];
        for (var i in builds) {
            data.push(['#' + builds[i].build_id, parseInt(builds[i].meta_value.LOC), parseInt(builds[i].meta_value.CLOC), parseInt(builds[i].meta_value.NCLOC), parseInt(builds[i].meta_value.LLOC)]);
        }

        var data = google.visualization.arrayToDataTable(data);
        var options = {
            hAxis: {title: 'Builds'},
            vAxis: {title: 'Lines'},
            backgroundColor: { fill: 'transparent' },
            height: 275
        };

        var chart = new google.visualization.LineChart(document.getElementById('phploc-lines'));
        chart.draw(data, options);
    }
});

PHPCI.registerPlugin(new locPlugin());
