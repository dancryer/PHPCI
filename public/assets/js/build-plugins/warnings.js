var warningsPlugin = PHPCI.UiPlugin.extend({
    id: 'build-warnings-chart',
    css: 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
    title: 'Quality Trend',
    keys: {
        'phpmd-warnings': 'PHPMD Warnings',
        'phpcs-warnings': 'PHPCS Warnings',
        'phpcs-errors': 'PHPCS Errors',
        'phplint-errors': 'PHPLint Errors',
        'phpunit-errors': 'PHPUnit Errors',
        'phpdoccheck-warnings': 'PHP Docblock Checker Warnings'
    },
    data: {},
    displayOnUpdate: false,
    rendered: false,

    register: function() {
        var self = this;

        var queries = [];
        for (var key in self.keys) {
          queries.push(PHPCI.registerQuery(key, -1, {num_builds: 10, key: key}));
        }

        $(window).on('phpmd-warnings phpcs-warnings phpcs-errors phplint-errors phpunit-errors phpdoccheck-warnings', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function(data) {
            if (!self.rendered && data.queryData.status > 1) {
                self.displayOnUpdate = true;
                for (var query in queries) {
                  queries[query]();
                }
            }
        });

        google.load("visualization", "1", {packages:["corechart"]});
    },

    render: function() {
        return $('<div id="build-warnings"></div>').text('This chart will display once the build has completed.');
    },

    onUpdate: function(e) {
        var self = this;
        var builds = e.queryData;

        if (!builds || !builds.length) {
            return;
        }

        for (var i in builds) {
            var buildId = builds[i]['build_id'];
            var metaKey = builds[i]['meta_key'];
            var metaVal = builds[i]['meta_value'];

            if (!self.data[buildId]) {
                self.data[buildId] = {};
            }

            self.data[buildId][metaKey] = metaVal;
        }

        if (self.displayOnUpdate) {
            self.displayChart();
        }
    },

    displayChart: function() {
        var self = this;
        self.rendered = true;

        $('#build-warnings').empty().animate({height: '275px'});

        var titles = ['Build'];
        for (var key in self.keys) {
            titles.push(self.keys[key]);
        }

        var data = [titles];
        for (var build in self.data) {
            var thisBuild = ['#' + build];

            for (var key in self.keys) {
                thisBuild.push(parseInt(self.data[build][key]));
            }

            data.push(thisBuild);
        }

        var data = google.visualization.arrayToDataTable(data);
        var options = {
            hAxis: {title: 'Builds'},
            vAxis: {title: 'Warnings / Errors'},
            backgroundColor: { fill: 'transparent' },
            height: 275,
            pointSize: 3
        };

        var chart = new google.visualization.LineChart(document.getElementById('build-warnings'));
        chart.draw(data, options);
    }
});

PHPCI.registerPlugin(new warningsPlugin());
