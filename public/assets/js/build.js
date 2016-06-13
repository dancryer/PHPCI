var Build = Class.extend({
    buildId: null,
    plugins: {},
    observers: {},
    buildData: {},
    queries: {},
    updateInterval: null,

    init: function(build) {
        var self = this;
        self.buildId = build;
    },

    setupBuild: function (buildData, linkTemplate) {
        var self = this;
        self.buildData = buildData;
        self.fileLinkTemplate = linkTemplate;

        self.registerQuery('build-updated', 10);

        $(window).on('build-updated', function(data) {

            self.buildData = data.queryData;

            // If the build has finished, stop updating every 10 seconds:
            if (self.buildData.status > 1) {
                self.cancelQuery('build-updated');
                $(window).trigger({type: 'build-complete'});
            }

            $('.build-duration').data('duration', self.buildData.duration ? self.buildData.duration : '');
            $('.build-started').data('date', self.buildData.started ? self.buildData.started : '');
            $('.build-finished').data('date', self.buildData.finished ? self.buildData.finished : '');
            $('#log pre').html(self.buildData.log);
            $('.errors-table tbody').append(self.buildData.error_html);

            if (self.buildData.errors == 0) {
                $('.errors-label').hide();
            } else {
                $('.errors-label').text(self.buildData.errors);
                $('.errors-label').show();
            }

            switch (self.buildData.status) {
                case 0:
                    $('body').removeClass('skin-red skin-green skin-yellow');
                    $('body').addClass('skin-blue');
                    break;

                case 1:
                    $('body').removeClass('skin-red skin-green skin-blue');
                    $('body').addClass('skin-yellow');
                    break;

                case 2:
                    $('body').removeClass('skin-red skin-blue skin-yellow');
                    $('body').addClass('skin-green');
                    break;

                case 3:
                    $('body').removeClass('skin-blue skin-green skin-yellow');
                    $('body').addClass('skin-red');
                    break;

            }

            PHPCI.uiUpdated();
        });
    },

    registerQuery: function(name, seconds, query) {
        var self = this;
        var uri = 'build/meta/' + self.buildId;
        var query = query || {};

        var cb = function() {
            var fullUri = window.PHPCI_URL + uri;

            if (name == 'build-updated') {
                fullUri = window.PHPCI_URL + 'build/data/' + self.buildId + '?since=' + self.buildData.since;
            }

            $.ajax({
                dataType: "json",
                url: fullUri,
                data: query,
                success: function(data) {
                    $(window).trigger({type: name, queryData: data});
                },
                error: handleFailedAjax
            });
        };

        if (seconds != -1) {
            self.queries[name] = setInterval(cb, seconds * 1000);
        }

        return cb;
    },

    cancelQuery: function (name) {
        clearInterval(this.queries[name]);
    },

    registerPlugin: function(plugin) {
        this.plugins[plugin.id] = plugin;
        plugin.register();
    },

    storePluginOrder: function () {
        var renderOrder = [];

        $('.ui-plugin > div').each(function() {
            renderOrder.push($(this).attr('id'));
        });

        localStorage.setItem('phpci-plugin-order', JSON.stringify(renderOrder));
    },

    renderPlugins: function() {
        var self = this;
        var rendered = [];
        var renderOrder = localStorage.getItem('phpci-plugin-order');

        if (renderOrder) {
            renderOrder = JSON.parse(renderOrder);
        } else {
            renderOrder = ['build-lines-chart', 'build-warnings-chart'];
        }

        for (var idx in renderOrder) {
            var key = renderOrder[idx];

            // Plugins have changed, clear the order.
            if (typeof self.plugins[key] == 'undefined') {
                localStorage.setItem('phpci-plugin-order', []);
            }

            self.renderPlugin(self.plugins[key]);
            rendered.push(key);
        }

        for (var key in this.plugins) {
            if (rendered.indexOf(key) == -1) {
                self.renderPlugin(self.plugins[key]);
            }
        }

        $('#plugins').sortable({
            handle: '.box-title',
            connectWith: '#plugins',
            update: self.storePluginOrder
        });

        $(window).trigger({type: 'build-updated', queryData: self.buildData});
    },

    renderPlugin: function(plugin) {
        var output = plugin.render();

        if (!plugin.box) {
            output = $('<div class="box-body"></div>').append(output);
        }

        var container = $('<div></div>').addClass('ui-plugin ' + plugin.css).attr('id', plugin.id);
        var content = $('<div></div>').append(output);
        content.addClass('box box-default');

        if (plugin.title) {
            content.prepend('<div class="box-header"><h3 class="box-title">'+plugin.title+'</h3></div>');
        }

        container.append(content);

        $('#plugins').append(container);
    },

    UiPlugin: Class.extend({
        id: null,
        css: 'col-lg-4 col-md-6 col-sm-12 col-xs-12',
        box: false,

        init: function(){
        },

        register: function() {
            var self = this;

            $(window).on('build-updated', function(data) {
                self.onUpdate(data);
            });
        },

        render: function () {
            return '';
        },

        onUpdate: function (build) {

        }
    })
});
