var codeceptionPlugin = PHPCI.UiPlugin.extend({
    id: 'build-codeception-errors',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: 'Codeception',
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('codeception-data', -1, {key: 'codeception-data'});

        $(window).on('codeception-data', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function() {
            if (!self.rendered) {
                self.displayOnUpdate = true;
                query();
            }
        });
    },

    render: function() {
        return $('<table class="table table-striped" id="codeception-data">' +
            '<thead>' +
            '<tr><th>'+Lang.get('codeception_suite')+'</th>' +
            '<th>'+Lang.get('codeception_feature')+'</th>' +
            '<th>'+Lang.get('codeception_time')+'</th></tr>' +
            '</thead><tbody></tbody><tfoot></tfoot></table>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-codeception-errors').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var tests = this.lastData[0].meta_value;
        var tbody = $('#codeception-data tbody');
        tbody.empty();

        if (tests.length == 0) {
            $('#build-codeception-errors').hide();
            return;
        }

        for (var i in tests) {

            var row = $('<tr>' +
                '<td><strong>'+tests[i].suite+'</strong</td>' +
                '<td>'+tests[i].feature+'</td>' +
                '<td data-toggle="tooltip" data-html="true" data-container="body" title="'
                    +tests[i].class+'::'+tests[i].name+(tests[i].message ? ' - '+tests[i].message : '') +
                '">'+tests[i].time+'</td>'+
                '</tr>');

            if (!tests[i].pass) {
                row.addClass('danger');
            } else {
                row.addClass('success');
            }

            tbody.append(row);
        }

        $('#build-codeception-errors').show();
        $('#build-codeception-errors td').tooltip();
});

ActiveBuild.registerPlugin(new codeceptionPlugin());
