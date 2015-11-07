var warningsPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-warnings-chart',
    css: 'col-xs-12',
    title: Lang.get('quality_trend'),
    keys: {
        'codeception-errors': Lang.get('codeception_errors'),
        'phplint-errors': Lang.get('phplint_errors'),
        'phpunit-errors': Lang.get('phpunit_errors'),
        'phptallint-errors': Lang.get('phptal_errors'),
        'phptallint-warnings': Lang.get('phptal_warnings')
    },
    data: {},
    displayOnUpdate: false,
    rendered: false,
    chartData: null,

    register: function() {
        var self = this;

        var queries = [];
        for (var key in self.keys) {
          queries.push(ActiveBuild.registerQuery(key, -1, {num_builds: 10, key: key}));
        }

        $(window).on('codeception-errors phptallint-warnings phptallint-errors phplint-errors phpunit-errors', function(data) {
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
    },

    render: function() {
        var self = this;
        var container = $('<div id="build-warnings" style="width: 100%; height: 300px"></div>');
        container.append('<canvas id="build-warnings-linechart" style="width: 100%; height: 300px"></canvas>');

        $(document).on('shown.bs.tab', function () {
            $('#build-warnings-chart').hide();
            self.drawChart();
        });

        return container;
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

        var colors = ['#4D4D4D', '#5DA5DA', '#FAA43A', '#60BD68', '#F17CB0', '#B2912F', '#B276B2', '#DECF3F', '#F15854'];

        self.chartData = {
            labels: [],
            datasets: []
        };

        for (var key in self.keys) {
            var color = colors.shift();

            self.chartData.datasets.push({
                label: self.keys[key],
                strokeColor: color,
                pointColor: color,
                data: []
            });
        }

        for (var build in self.data) {
            self.chartData.labels.push('Build ' + build);

            var i = 0;
            for (var key in self.keys) {

                self.chartData.datasets[i].data.push(parseInt(self.data[build][key]));
                i++;
            }
        }

        self.drawChart();
    },

    drawChart: function () {
        var self = this;

        if ($('#information').hasClass('active') && self.chartData) {
            $('#build-warnings-chart').show();

            var ctx = $("#build-warnings-linechart").get(0).getContext("2d");
            var buildWarningsChart = new Chart(ctx);

            buildWarningsChart.Line(self.chartData, {
                datasetFill: false,
                multiTooltipTemplate: "<%=datasetLabel%>: <%= value %>"
            });
        }
    }
});

ActiveBuild.registerPlugin(new warningsPlugin());
