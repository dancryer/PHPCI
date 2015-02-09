var phpdoccheckPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-phpdoccheck-warnings',
    css: 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    title: Lang.get('phpdoccheck'),
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('phpdoccheck-data', -1, {key: 'phpdoccheck-data'})

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
        return $('<div class="table-responsive"><table class="table" id="phpdoccheck-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>'+Lang.get('file')+'</th>' +
            '   <th>'+Lang.get('line')+'</th>' +
            '   <th>'+Lang.get('class')+'</th>' +
            '   <th>'+Lang.get('method')+'</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table></div>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-phpdoccheck-warnings').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var errors = this.lastData[0].meta_value;
        var tbody = $('#phpdoccheck-data tbody');
        tbody.empty();

        if (errors.length == 0) {
            $('#build-phpdoccheck-warnings').hide();
            return;
        }

        for (var i in errors) {
            var file = errors[i].file;

            if (ActiveBuild.fileLinkTemplate) {
                var fileLink = ActiveBuild.fileLinkTemplate.replace('{FILE}', file);
                fileLink = fileLink.replace('{LINE}', errors[i].line);

                file = '<a target="_blank" href="'+fileLink+'">' + file + '</a>';
            }

            var row = $('<tr>' +
                '<td>'+file+'</td>' +
                '<td>'+errors[i].line+'</td>' +
                '<td>'+errors[i].class+'</td>' +
                '<td>'+(errors[i].method ? errors[i].method : '')+'</td></tr>');

            if (errors[i].type == 'method') {
                row.addClass('danger');
            } else {
                row.addClass('warning');
            }

            tbody.append(row);
        }

        $('#build-phpdoccheck-warnings').show();
    }
});

ActiveBuild.registerPlugin(new phpdoccheckPlugin());
