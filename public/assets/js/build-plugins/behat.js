var BehatPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-behat',
    css: 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    title: Lang.get('behat'),
    lastData: null,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('behat-data', -1, {key: 'behat-data'})

        $(window).on('behat-data', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function() {
            if (!self.rendered) {
                query();
            }
        });
    },

    render: function() {
        return $('<div class="table-responsive"><table class="table" id="behat-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>'+Lang.get('file')+'</th>' +
            '   <th>'+Lang.get('line')+'</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table></div>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-behat').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var errors = this.lastData[0].meta_value;
        var tbody = $('#behat-data tbody');
        tbody.empty();

        if (errors.length == 0) {
            $('#build-behat').hide();
            return;
        }

        for (var i in errors) {
            var file = errors[i].file;

            if (ActiveBuild.fileLinkTemplate) {
                var fileLink = ActiveBuild.fileLinkTemplate.replace('{FILE}', file);
                fileLink = fileLink.replace('{LINE}', errors[i].line);

                file = '<a target="_blank" href="'+fileLink+'">' + file + '</a>';
            }

            var row = $('<tr class="danger">' +
                '<td>'+file+'</td>' +
                '<td>'+errors[i].line+'</td>' +
            '</tr>');

            tbody.append(row);
        }

        $('#build-behat').show();
    }
});

ActiveBuild.registerPlugin(new BehatPlugin());
