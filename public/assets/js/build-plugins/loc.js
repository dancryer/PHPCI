var locPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-lines-chart',
    css: 'col-xs-12',
    title: Lang.get('lines_of_code'),
    lastData: null,
    displayOnUpdate: false,
    rendered: false,
    chartData: null,

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
    },

    render: function() {
        var self = this;
        var container = $('<div id="phploc-lines" style="width: 100%; height: 300px"></div>');
        container.append('<canvas id="phploc-lines-chart" style="width: 100%; height: 300px"></canvas>');

        $(document).on('shown.bs.tab', function () {
            $('#build-lines-chart').hide();
            self.drawChart();
        });

        return container;
    },

    onUpdate: function(e) {
        this.lastData = e.queryData;
        this.displayChart();
    },

    displayChart: function() {
        var self = this;
        var builds = this.lastData;
        self.rendered = true;

        self.chartData = {
            labels: [],
            datasets: [
                {
                    label: Lang.get('lines'),
                    strokeColor: "rgba(60,141,188,1)",
                    pointColor: "rgba(60,141,188,1)",
                    data: []
                },
                {
                    label: Lang.get('logical_lines'),
                    strokeColor: "rgba(245,105,84,1)",
                    pointColor: "rgba(245,105,84,1)",
                    data: []
                },
                {
                    label: Lang.get('comment_lines'),
                    strokeColor: "rgba(0,166,90,1)",
                    pointColor: "rgba(0,166,90,1)",
                    data: []
                },
                {
                    label: Lang.get('noncomment_lines'),
                    strokeColor: "rgba(0,192,239,1)",
                    pointColor: "rgba(0,192,239,1)",
                    data: []
                }
            ]
        };

        for (var i in builds) {
            self.chartData.labels.push('Build ' + builds[i].build_id);
            self.chartData.datasets[0].data.push(builds[i].meta_value.LOC);
            self.chartData.datasets[1].data.push(builds[i].meta_value.LLOC);
            self.chartData.datasets[2].data.push(builds[i].meta_value.CLOC);
            self.chartData.datasets[3].data.push(builds[i].meta_value.NCLOC);
        }

        self.drawChart();
    },

    drawChart: function () {
        var self = this;

        if ($('#information').hasClass('active') && self.chartData && self.lastData) {
            $('#build-lines-chart').show();

            var ctx = $("#phploc-lines-chart").get(0).getContext("2d");
            var phpLocChart = new Chart(ctx);

            phpLocChart.Line(self.chartData, {
                datasetFill: false,
                multiTooltipTemplate: "<%=datasetLabel%>: <%= value %>"
            });
        }
    }
});

ActiveBuild.registerPlugin(new locPlugin());
