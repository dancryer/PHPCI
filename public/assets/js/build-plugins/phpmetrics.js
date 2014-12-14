var phpmetricsPlugin = PHPCI.UiPlugin.extend({
    id: 'build-lines-chart',
    css: 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
    title: 'PhpMetrics Trend',
    lastData: null,
    displayOnUpdate: false,
    rendered: false,

    register: function() {
        var self = this;
        var query = PHPCI.registerQuery('phpmetrics-lines', -1, {num_builds: 10, key: 'phpmetrics'})

        $(window).on('phpmetrics-lines', function(data) {
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
        return $('<div id="phpmetrics-lines"></div>').text('This chart will display once the build has completed.');
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

        $('#phpmetrics-lines').empty().animate({height: '275px'});

        var titles = ['ID', 'Maintenability', 'LCOM', 'Difficulty', 'Bugs', 'Intelligent content'];
        var data = [titles];
        console.log(builds);
        for (var i in builds) {
            data.push(
                [
                    '#' + builds[i].build_id
                    , parseInt(builds[i].meta_value.maintenabilityIndex)
                    , parseInt(builds[i].meta_value.lcom)
                    , parseInt(builds[i].meta_value.difficulty)
                    , parseInt(builds[i].meta_value.bugs)
                    , parseFloat(builds[i].meta_value.intelligentContent)
                ]
            );
        }

        var data = google.visualization.arrayToDataTable(data);
        var options = {
            hAxis: {title: 'Builds'},
            vAxis: {title: 'Lines'},
            backgroundColor: { fill: 'transparent' },
            height: 275
        };

        var chart = new google.visualization.LineChart(document.getElementById('phpmetrics-lines'));
        chart.draw(data, options);
    }
});

PHPCI.registerPlugin(new phpmetricsPlugin());



// PhpMetrics Bubbles
// --------------
// WIP :
//
//var phpmetricsPlugin = PHPCI.UiPlugin.extend({
//    id: 'build-log',
//    css: 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
//    title: 'Maintenability chart',
//    lastData: null,
//    displayOnUpdate: false,
//    rendered: false,
//
//    register: function() {
//        var self = this;
//        var query = PHPCI.registerQuery('phpmetrics-bubbles', -1, {num_builds: 10, key: 'phpmetrics-bubbles'})
//
//        $(window).on('phpmetrics-bubbles', function(data) {
//            self.onUpdate(data);
//        });
//
//        $(window).on('build-updated', function(data) {
//            if (data.queryData.status > 1 && !self.rendered) {
//                query();
//            }
//        });
//    },
//
//    render: function() {
//        return $('<div id="phpmetrics-bubbles"><embed type="image/svg+xml" src="/build/phpmetrics/chart-bubbles.svg" style="width:100%; height:100%" /></div>');
//    },
//
//    onUpdate: function(e) {
//    }
//});
//
//PHPCI.registerPlugin(new phpmetricsPlugin());
//
