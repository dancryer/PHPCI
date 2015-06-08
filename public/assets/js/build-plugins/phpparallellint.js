var phplintPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-phpparalelllint',
    css: 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    title: Lang.get('phpparalelllint'),
    lastData: null,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('phplint-data', -1, {key: 'phplint-data'})

        $(window).on('phplint-data', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function() {
            if (!self.rendered) {
                query();
            }
        });
    },

    render: function() {
        return $('<div class="table-responsive"><table class="table" id="phplint-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>'+Lang.get('file')+'</th>' +
            '   <th>'+Lang.get('line')+'</th>' +
            '   <th>'+Lang.get('message')+'</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table></div>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-phplint').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var errors = this.lastData[0].meta_value;
        var tbody = $('#phplint-data tbody');
        tbody.empty();

        if (errors.length == 0) {
            $('#build-phplint').hide();
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
                '<td>'+errors[i].message+'</td></tr>');

            if (errors[i].type == 'ERROR') {
                row.addClass('danger');
            }

            tbody.append(row);
        }

        $('#build-phplint').show();
    }
});

ActiveBuild.registerPlugin(new phplintPlugin());
