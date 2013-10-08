var plugin = PHPCI.UiPlugin.extend({
    id: 'build-warnings-chart',
    css: 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
    title: 'Quality Trend',
    data: {},
    keys: null,
    displayOnUpdate: false,

    register: function() {
        var self = this;
        var query1 = PHPCI.registerQuery('phpmd-warnings', -1, {num_builds: 10, key: 'phpmd-warnings'})
        var query2 = PHPCI.registerQuery('phpcs-warnings', -1, {num_builds: 10, key: 'phpcs-warnings'})
        var query3 = PHPCI.registerQuery('phpcs-errors', -1, {num_builds: 10, key: 'phpcs-errors'})

        $(window).on('phpmd-warnings phpcs-warnings phpcs-errors', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function(data) {
            if (data.queryData.status > 1) {
                self.displayOnUpdate = true;
                query1();
                query2();
                query3();
            }
        });

        google.load("visualization", "1", {packages:["corechart"]});
    },

    render: function() {
        return $('<div id="build-warnings"></div>').text('This chart will display once the build has completed.');
    },

    onUpdate: function(e) {
        var self = this;
        var build = e.queryData;

        if (!build || !build.length) {
            return;
        }

        for (var i in build) {
            var buildId = build[i]['build_id'];
            var metaKey = build[i]['meta_key'];
            var metaVal = build[i]['meta_value'];

            if (!self.data[buildId]) {
                self.data[buildId] = {};
            }

            self.data[buildId][metaKey] = metaVal;
            self.keys = Object.keys(self.data[buildId]);
        }

        if (self.displayOnUpdate) {
            self.displayChart();
        }
    },

    displayChart: function() {
        var self = this;

        $('#build-warnings').empty().animate({height: '275px'});

        var titles = ['Build'];
        var keys = self.keys;

        for (var i in keys) {
            var t = {'phpmd-warnings': 'PHPMD Warnings', 'phpcs-warnings': 'PHPCS Warnings', 'phpcs-errors': 'PHPCS Errors'};
            titles.push(t[keys[i]]);
        }

        var data = [titles];

        for (var build in self.data) {
            var thisBuild = ['#' + build];

            for (var i in keys) {
                thisBuild.push(parseInt(self.data[build][keys[i]]));
            }

            data.push(thisBuild);
        }

        var data = google.visualization.arrayToDataTable(data);
        var options = {
            hAxis: {title: 'Build'},
            vAxis: {title: 'Warnings'},
            backgroundColor: { fill: 'transparent' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('build-warnings'));
        chart.draw(data, options);
    }
});

PHPCI.registerPlugin(new plugin());