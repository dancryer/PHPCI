var cakephpPlugin = ActiveBuild.UiPlugin.extend({
    id: 'build-cakephp',
    css: 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
    title: "CakePHP 2 Testing output",
    lastData: null,
    displayOnUpdate: false,
    box: true,
    rendered: false,

    register: function() {
        var self = this;
        var query = ActiveBuild.registerQuery('cakephp-data', -1, {key: 'cakephp-data'});
        var query2 = ActiveBuild.registerQuery('cakephp-errors', -1, {key: 'cakephp-errors'});

        $(window).on('cakephp-data', function(data) {
            self.onUpdate(data);
        });
        
        $(window).on('cakephp-errors', function(data) {
            self.onUpdateErrors(data);
        });

        $(window).on('build-updated', function() {
            if (!self.rendered) {
                self.displayOnUpdate = true;
                query();
                query2();
            }
        });
    },

    render: function() {

        return $('<div class="table-responsive">' +
            ' <table class="table table-bordered" id="cakephp-data">' +
            '   <thead>' +
            '     <tr>' +
            '       <th colspan="2">Listado de pruebas realizadas</th>' +
            '       <th>Total/Falladas: <span id="build-cakephp-total"></span>/<span id="build-cakephp-failed"></span>' +
            '     </tr>' +
            '    </thead>' +
            '    <tbody>' + 
            '    </tbody>' + 
            ' </table>' + 
            '</div>');
    },

    onUpdate: function(e) {
        if (!e.queryData) {
            $('#build-cakephp-data').hide();
            return;
        }

        this.rendered = true;
        this.lastData = e.queryData;

        var tests = this.lastData[0].meta_value;
        var tbody = $('#cakephp-data tbody');
        tbody.empty();

        if (tests.length == 0) {
            $('#build-cakephp-data').hide();
            return;
        }
        
        $("#build-cakephp-total").html(tests.length);

        for (var i in tests) {
            var row = $('<tr>' +
                ' <td>#' + i + '</td>' +
                ' <td><strong>'+tests[i].suite+'::'+tests[i].test+'</strong></td>' +
                ' <td>'+(tests[i].message || '')+'</td>' +
                '</tr>');

            if (!tests[i].pass) {
                row.addClass('danger');
            } else {
                row.addClass('success');
            }

            tbody.append(row);
        }

        $('#build-cakephp-data').show();
    },
    
    onUpdateErrors: function(e) {
        if (!e.queryData) {
            $('#build-cakephp-errors').hide();
            return;
        }
        var failed_tests = e.queryData[0].meta_value;
        $("#build-cakephp-failed").html(failed_tests);
    }
});

ActiveBuild.registerPlugin(new cakephpPlugin());
