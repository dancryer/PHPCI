var phpcpdPlugin = PHPCI.UiPlugin.extend({
    id: 'build-phpcpd',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: 'PHP Copy/Paste Detector',
    lastData: null,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = PHPCI.registerQuery('phpcpd-data', -1, {key: 'phpcpd-data'})

        $(window).on('phpcpd-data', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function() {
            if (!self.rendered) {
                query();
            }
        });
    },

    render: function() {

        return $('<table class="table table-striped" id="phpcpd-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>File</th>' +
            '   <th>Start</th>' +
            '   <th>End</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table>');

    },

    onUpdate: function(e) {
        if (!e.queryData) {
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var errors = this.lastData[0].meta_value;
        var tbody = $('#phpcpd-data tbody');
        tbody.empty();

        var rowClass = 'danger';
        for (var i in errors) {
            var file = errors[i].file;

            if (PHPCI.fileLinkTemplate) {
                var fileLink = PHPCI.fileLinkTemplate.replace('{FILE}', file);
                fileLink = fileLink.replace('{LINE}', errors[i].line_start);

                file = '<a target="_blank" href="'+fileLink+'">' + file + '</a>';
            }

            var label = 'From';

            if (i % 2 > 0) {
                label = 'To';
            }
            else {
                rowClass = (rowClass == 'warning' ? 'danger' : 'warning');
            }

            var row = $('<tr>' +
                '<td><strong>' + label + '</strong>: '+file+'</td>' +
                '<td>'+errors[i].line_start+'</td>' +
                '<td>'+errors[i].line_end+'</td></tr>');

            row.addClass(rowClass);

            tbody.append(row);
        }
    }
});

PHPCI.registerPlugin(new phpcpdPlugin());
