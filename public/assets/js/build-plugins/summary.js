var SummaryPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-summary',
    css: 'col-xs-12',
    title: Lang.get('build-summary'),
    box: true,
    statusIcons: [ 'fa-clock-o', 'fa-cogs', 'fa-check', 'fa-remove' ],
    statusLabels: [ Lang.get('pending'), Lang.get('running'), Lang.get('successful'), Lang.get('failed') ],
    statusClasses: ['text-blue', 'text-yellow', 'text-green', 'text-red'],

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('plugin-summary', 5, {key: 'plugin-summary'})

        $(window).on('plugin-summary', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function() {
            query();
        });
    },

    render: function() {
        return $(
            '<div class="table-responsive"><table class="table" id="plugin-summary">' +
            '<thead><tr>' +
                    '<th>'+Lang.get('stage')+'</th>' +
                    '<th>'+Lang.get('plugin')+'</th>' +
                    '<th>'+Lang.get('status')+'</th>' +
                    '<th class="text-right">'+Lang.get('duration')+' (s)</th>' +
            '</tr></thead><tbody></tbody></table></div>'
        );
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-summary').hide();
            return;
        }

        var tbody = $('#plugin-summary tbody'),
            summary = e.queryData[0].meta_value;
        tbody.empty();

        for(var stage in summary) {
            for(var plugin in summary[stage]) {
                var data = summary[stage][plugin],
                    duration = data.started ? ((data.ended || Math.floor(Date.now()/1000)) - data.started) : '-';
                tbody.append(
                    '<tr>' +
                        '<td>' + Lang.get('stage_'+stage) + '</td>' +
                        '<td>' + plugin + '</td>' +
                        '<td><span  class="' + this.statusClasses[data.status] + '">' +
                            '<i class="fa ' + this.statusIcons[data.status] + '"></i>&nbsp;' +
                            this.statusLabels[data.status] +
                        '</span></td>' +
                        '<td class="text-right">' + duration + '</td>' +
                    '</tr>'
                );
            }
        }

        $('#build-summary').show();
    }
});

ActiveBuild.registerPlugin(new SummaryPlugin());
