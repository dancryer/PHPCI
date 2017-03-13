var lintPlugin = PHPCI.UiPlugin.extend({
    id: 'build-lint-warnings',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: 'PHP Lint',
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = PHPCI.registerQuery('phplint-data', -1, {key: 'phplint-data'})

        $(window).on('phplint-data', function(data) {
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

        return $('<table class="table table-striped" id="phplint-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>File</th>' +
	    '   <th>Line</th>' +
            '   <th>Message</th>' +
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
        var tbody = $('#phplint-data tbody');
        tbody.empty();

        for (var i in errors) {
            var file = errors[i].file;

            if (PHPCI.fileLinkTemplate) {
                var fileLink = PHPCI.fileLinkTemplate.replace('{FILE}', file);
                fileLink = fileLink.replace('{LINE}', errors[i].line);

                file = '<a target="_blank" href="'+fileLink+'">' + file + '</a>';
            }

            var row = $('<tr>' +
                '<td>'+file+'</td>' +
                '<td>'+errors[i].line+'</td>' +
                '<td>'+errors[i].message+'</td></tr>');

            tbody.append(row);
        }
    }
});

PHPCI.registerPlugin(new lintPlugin());
