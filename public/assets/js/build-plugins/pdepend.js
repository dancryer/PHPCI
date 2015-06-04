var pdependPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-pdepend',
    css: 'col-lg-6 col-md-6 col-sm-12 col-xs-12',
    title: Lang.get('pdepend'),
    lastData: null,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('pdepend-data', -1, {key: 'pdepend-data'});

        $(window).on('pdepend-data', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function() {

            if (!self.rendered) {
                query();
            }
        });

    },

    render: function() {

        return $('<div id="pdepend-chart">' +
                '<h4>' + Lang.get('abstraction_instability_chart') + '</h4>' +
                '<div id="pdepend-chart1">' + Lang.get('chart_display') + '</div>' +
                '<h4>' + Lang.get('overview_pyramid') + '</h4>' +
                '<div id="pdepend-chart2">' + Lang.get('chart_display') + '</div>' +
                '</div>');

    },

    onUpdate: function(e) {

        if (!e.queryData) {
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        $('#pdepend-chart1').html(this.lastData[0].meta_value.chart);
        $('#pdepend-chart2').html(this.lastData[0].meta_value.pyramid);

    },

 
});

ActiveBuild.registerPlugin(new pdependPlugin());
