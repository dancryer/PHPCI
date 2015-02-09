var phpcpdPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-phpcpd',
    css: 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    title: Lang.get('phpcpd'),
    lastData: null,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('phpcpd-data', -1, {key: 'phpcpd-data'})

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

        return $('<div class="table-responsive"><table class="table" id="phpcpd-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>'+Lang.get('file')+'</th>' +
            '   <th>'+Lang.get('start')+'</th>' +
            '   <th>'+Lang.get('end')+'</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table></div>');

    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-phpcpd').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var errors = this.lastData[0].meta_value;
        var tbody = $('#phpcpd-data tbody');
        tbody.empty();

        var rowClass = 'danger';

        if (errors.length == 0) {
            $('#build-phpcpd').hide();
            return;
        }

        for (var i in errors) {
            var file = errors[i].file;

            if (ActiveBuild.fileLinkTemplate) {
                var fileLink = ActiveBuild.fileLinkTemplate.replace('{FILE}', file);
                fileLink = fileLink.replace('{LINE}', errors[i].line_start);

                file = '<a target="_blank" href="'+fileLink+'">' + file + '</a>';
            }

            var label = Lang.get('from');

            if (i % 2 > 0) {
                label = Lang.get('to');
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

        $('#build-phpcpd').show();
    }
});

ActiveBuild.registerPlugin(new phpcpdPlugin());
