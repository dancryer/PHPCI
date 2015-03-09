var phpmdPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-phpmd-warnings',
    css: 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    title: Lang.get('phpmd'),
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('phpmd-data', -1, {key: 'phpmd-data'})

        $(window).on('phpmd-data', function(data) {
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

        return $('<div class="table-responsive"><table class="table" id="phpmd-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>'+Lang.get('file')+'</th>' +
            '   <th>'+Lang.get('start')+'</th>' +
            '   <th>'+Lang.get('end')+'</th>' +
            '   <th>'+Lang.get('message')+'</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table></div>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-phpmd-warnings').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var errors = this.lastData[0].meta_value;
        var tbody = $('#phpmd-data tbody');
        tbody.empty();

        if (errors.length == 0) {
            $('#build-phpmd-warnings').hide();
            return;
        }

        for (var i in errors) {
            var file = errors[i].file;

            if (ActiveBuild.fileLinkTemplate) {
                var fileLink = ActiveBuild.fileLinkTemplate.replace('{FILE}', file);
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

        $('#build-phpmd-warnings').show();
    }
});

ActiveBuild.registerPlugin(new phpmdPlugin());
