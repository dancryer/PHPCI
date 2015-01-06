var phpunitPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-phpunit-errors',
    css: 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    title: Lang.get('phpunit'),
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,

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

        $(document).on('click', '#phpunit-filter-all', function() {
            $('#phpunit-data tbody tr').show();
        });

        $(document).on('click', '#phpunit-filter-pass', function() {
            $('#phpunit-data tbody tr').hide();
            $('#phpunit-data tbody tr.success').show();
        });

        $(document).on('click', '#phpunit-filter-fail', function() {
            $('#phpunit-data tbody tr').hide();
            $('#phpunit-data tbody tr.danger').show();
        });
    },

    render: function() {

        return $('<table class="table" id="phpunit-data">' +
            '<thead>' +
            '<tr>' +
            '   <th>' +
            '     '+Lang.get('test')+' <span id="phpunit-counter"></span>' +
            '   </th>' +
            '   <th>' +
            '      <div class="btn-group pull-right" data-toggle="buttons">' +
            '         <label class="btn btn-xs btn-default" id="phpunit-filter-all">' +
            '            <input type="radio" class="simple" name="phpunit-filter" autocomplete="off"> All' +
            '         </label>' +
            '         <label class="btn btn-xs btn-success" id="phpunit-filter-pass">' +
            '            <input type="radio" class="simple" name="phpunit-filter" autocomplete="off"> '+Lang.get('success') +
            '         </label>' +
            '         <label class="btn btn-xs btn-danger active" id="phpunit-filter-fail">' +
            '            <input type="radio" class="simple" name="phpunit-filter" autocomplete="off" checked> '+Lang.get('failed') +
            '         </label>' +
            '      </div>' +
            '   </th>' +
            '</tr>' +
            '</thead><tbody></tbody></table>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-phpunit-errors').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var tests = this.lastData[0].meta_value;
        var failed = 0;
        var tbody = $('#phpunit-data tbody');
        tbody.empty();

        if (tests.length == 0) {
            $('#build-phpunit-errors').hide();
            return;
        }

        for (var i in tests) {

            var row = $('<tr>' +
                '<td colspan="2"><strong>'+tests[i].suite+'' +
                '::'+tests[i].test+'</strong><br>' +
                ''+(tests[i].message || '')+'</td>' +
                '</tr>');

            if (!tests[i].pass) {
                row.addClass('danger');
                failed++;
            } else {
                row.addClass('success');
                row.css('display', 'none');
            }

            tbody.append(row);
        }
      
        $("#phpunit-counter").text('(' + Lang.get('x_of_x_failed_short', failed, tests.length) + ')');

        $('#build-phpunit-errors').show();
    }
});

ActiveBuild.registerPlugin(new phpunitPlugin());
