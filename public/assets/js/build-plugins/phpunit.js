var phpunitPlugin = PHPCI.UiPlugin.extend({
    id: 'build-phpunit-errors',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: 'PHPUnit',
    lastData: null,
    displayOnUpdate: false,
    box: true,

    register: function() {
        var self = this;
        var query = PHPCI.registerQuery('phpunit-data', -1, {key: 'phpunit-data'})

        $(window).on('phpunit-data', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function(data) {
            if (data.queryData.status > 1) {
                self.displayOnUpdate = true;
                query();
            }
        });
    },

    render: function() {

        return $('<table class="table table-striped" id="phpunit-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>Test</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table>');
    },

    onUpdate: function(e) {
        if (this.lastData && this.lastData[0]) {
            return;
        }

        this.lastData = e.queryData;

        var tests = this.lastData[0].meta_value;
        var tbody = $('#phpunit-data tbody');
        tbody.empty();

        for (var i in tests) {

            var row = $('<tr>' +
                '<td><strong>'+tests[i].suite+'' +
                '::'+tests[i].test+'</strong><br>' +
                ''+(tests[i].message || '')+'</td>' +
                '</tr>');

            if (!tests[i].pass) {
                row.addClass('danger');
            } else {
                row.addClass('success');
            }

            tbody.append(row);
        }
    }
});

PHPCI.registerPlugin(new phpunitPlugin());
