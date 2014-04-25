var phpmdPlugin = PHPCI.UiPlugin.extend({
    id: 'build-phpmd-warnings',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: 'PHP Mess Detector',
    lastData: null,
    displayOnUpdate: false,

    register: function() {
        var self = this;
        var query = PHPCI.registerQuery('phpmd-data', -1, {key: 'phpmd-data'})

        $(window).on('phpmd-data', function(data) {
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

        return $('<table class="table table-striped" id="phpmd-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>File</th>' +
            '   <th>Start</th>' +
            '   <th>End</th>' +
            '   <th>Message</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table>');
    },

    onUpdate: function(e) {
        if (this.lastData && this.lastData[0]) {
            return;
        }

        this.lastData = e.queryData;

        var errors = this.lastData[0].meta_value;
        var tbody = $('#phpmd-data tbody');
        tbody.empty();

        for (var i in errors) {
            var file = errors[i].file;

            if (PHPCI.fileLinkTemplate) {
                var fileLink = PHPCI.fileLinkTemplate.replace('{FILE}', file);
                fileLink = fileLink.replace('{LINE}', errors[i].line_start);

                file = '<a target="_blank" href="'+fileLink+'">' + file + '</a>';
            }

            var row = $('<tr>' +
                '<td>'+file+'</td>' +
                '<td>'+errors[i].line_start+'</td>' +
                '<td>'+errors[i].line_end+'</td>' +
                '<td>'+errors[i].message+'</td></tr>');

            tbody.append(row);
        }
    }
});

PHPCI.registerPlugin(new phpmdPlugin());
