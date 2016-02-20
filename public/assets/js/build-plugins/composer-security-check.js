var composerSecurityCheck = ActiveBuild.UiPlugin.extend({
    id: 'build-composer-security-check-errors',
    css: 'col-lg-6 col-md-6 col-sm-6 col-xs-6',
    title: Lang.get('Composer Security'),
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('composer-security-check-errors', -1, {key: 'composer-security-check-errors'})

        $(window).on('composer-security-check-errors', function(data) {
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

        return $('<div class="table-responsive"><table class="table" id="composer-security-check-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>'+Lang.get('Resultats')+'</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table></div>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-composer-security-check-errors').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var results = this.lastData[0].meta_value;
        var tbody = $('#composer-security-check-data tbody');
        tbody.empty();

        if (results.length == 0) {
            $('#build-composer-security-check-errors').hide();
            return;
        }

        console.log(results);
        for (var i in results) {
            var lib = results[i];
            var head = '<tr><th>'+i + ' ' + lib.version+'</th></tr>'
            tbody.append(head);
            console.log(lib);
            for (var j in lib.advisories) {
                var advise = lib.advisories[j]
                console.log(advise);
                var row = '<tr><td><a href="'+advise.link+'" target="_blank">'+advise.title+'</a></TD></tr>'
                tbody.append(row);
            }
        }

        $('#build-composer-security-check-errors').show();
    }
});

ActiveBuild.registerPlugin(new composerSecurityCheck());
