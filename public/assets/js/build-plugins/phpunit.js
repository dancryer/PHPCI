var phpunitPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-phpunit-errors',
    css: 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    title: Lang.get('phpunit'),
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,
    statusMap: {
        success : 'ok',
        fail: 'remove',
        error: 'warning-sign',
        todo: 'info-sign',
        skipped: 'exclamation-sign'
    },

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('phpunit-data', -1, {key: 'phpunit-data'})

        $(window).on('phpunit-data', function(data) {
            self.onUpdate(data);
        });

        $(window).on('build-updated', function() {
            if (!self.rendered) {
                self.displayOnUpdate = true;
                query();
            }
        });

        $(document).on('click', '#phpunit-data .test-toggle', function(ev) {
            var input = $(ev.target);
            $('#phpunit-data tbody ' + input.data('target')).toggle(input.prop('checked'));
        });
    },

    render: function() {

        return $('<div class="table-responsive"><table class="table" id="phpunit-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>'+Lang.get('test_message')+'</th>' +
            '</tr>' +
            '</thead><tbody></tbody></table></div>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-phpunit-errors').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var tests = this.lastData[0].meta_value;
        var thead = $('#phpunit-data thead tr');
        var tbody = $('#phpunit-data tbody');
        thead.empty().append('<th>'+Lang.get('test_message')+'</th>');
        tbody.empty();

        if (tests.length == 0) {
            $('#build-phpunit-errors').hide();
            return;
        }

        var counts = { success: 0, fail: 0, error: 0, skipped: 0, todo: 0 }, total = 0;

        for (var i in tests) {
            var content = $('<td colspan="3"></td>'),
                message = $('<div></div>').appendTo(content),
                severity = tests[i].severity || (tests[i].pass ? 'success' : 'failed');

            if (tests[i].message) {
                message.text(tests[i].message);
            } else if (tests[i].test && tests[i].suite) {
                message.text(tests[i].suite + '::' + tests[i].test);
            } else {
                message.html('<i>' + Lang.get('test_no_message') + '</i>');
            }

            if (tests[i].data) {
                content.append('<div>' + this.repr(tests[i].data) + '</div>');
            }

            $('<tr class="'+  severity + '"></tr>').append(content).appendTo(tbody);

            counts[severity]++;
            total++;
        }

        var checkboxes = $('<th/>');
        thead.append(checkboxes).append('<th>' + Lang.get('test_total', total) + '</th>');

        for (var key in counts) {
            var count = counts[key];
            if(count > 0) {
                checkboxes.append(
                    '<div style="float:left" class="' + key + '"><input type="checkbox" class="test-toggle" data-target=".' + key + '" ' +
                    (key !== 'success' ? ' checked' : '') + '/>&nbsp;' +
                    Lang.get('test_'+key, count)+ '</div> '
                );
            }
        }

        tbody.find('.success').hide();

        $('#build-phpunit-errors').show();
    },

    repr: function(data)
    {
        switch(typeof(data)) {
            case 'boolean':
                return '<span class="boolean">' + (data ? 'true' : 'false') + '</span>';
            case 'string':
                return '<span class="string">"' + data + '"</span>';
            case 'undefined': case null:
                return '<span class="null">null</span>';
            case 'object':
                var rows = [];
                if(data instanceof Array) {
                    for(var i in data) {
                        rows.push('<tr><td colspan="3">' + this.repr(data[i]) + ',</td></tr>');
                    }
                } else {
                    for(var key in data) {
                        rows.push(
                            '<tr>' +
                                '<td>' + this.repr(key) + '</td>' +
                                '<td>=&gt;</td>' +
                                '<td>' + this.repr(data[key]) + ',</td>' +
                            '</tr>');
                    }
                }
                return '<table>' +
                        '<tr><th colspan="3">array(</th></tr>' +
                        rows.join('') +
                        '<tr><th colspan="3">)</th></tr>' +
                    '</table>';
        }
        return '???';
    }
});

ActiveBuild.registerPlugin(new phpunitPlugin());
