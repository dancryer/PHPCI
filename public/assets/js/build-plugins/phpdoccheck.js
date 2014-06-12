var phpdoccheckPlugin = PHPCI.UiPlugin.extend({
    id: 'build-phpdoccheck-warnings',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: 'PHP Docblock Checker',
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = PHPCI.registerQuery('phpdoccheck-data', -1, {key: 'phpdoccheck-data'})

        $(window).on('phpdoccheck-data', function(data) {
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
        return $('<table class="table table-striped" id="phpdoccheck-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>Type</th>' +
            '   <th>File</th>' +
            '   <th>Line</th>' +
            '   <th>Class</th>' +
            '   <th>Method</th>' +
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
        var tbody = $('#phpdoccheck-data tbody');
        tbody.empty();

        for (var i in errors) {
            var file = errors[i].file;

            if (PHPCI.fileLinkTemplate) {
                var fileLink = PHPCI.fileLinkTemplate.replace('{FILE}', file);
                fileLink = fileLink.replace('{LINE}', errors[i].line);

                file = '<a target="_blank" href="'+fileLink+'">' + file + '</a>';
            }

            var row = $('<tr>' +
                '<td>'+errors[i].type+'</td>' +
                '<td>'+file+'</td>' +
                '<td>'+errors[i].line+'</td>' +
                '<td>'+errors[i].class+'</td>' +
                '<td>'+errors[i].method+'</td></tr>');

            if (errors[i].type == 'method') {
                row.addClass('danger');
            } else {
                row.addClass('warning');
            }

            tbody.append(row);
        }
    }
});

PHPCI.registerPlugin(new phpdoccheckPlugin());
