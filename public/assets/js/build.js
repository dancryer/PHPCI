var Build = Class.extend({
    buildId: null,
    plugins: {},
    observers: {},
    buildData: {},
    queries: {},
    updateInterval: null,

    init: function(build) {
        var self = this;
        this.buildId = build;
        this.registerQuery('build-updated', 10);

        $(window).on('build-updated', function(data) {

            // If the build has finished, stop updating every 10 seconds:
            if (data.queryData.status > 1) {
                self.cancelQuery('build-updated');
                $(window).trigger({type: 'build-complete'});
            }

        });
    },

    registerQuery: function(name, seconds, query) {
        var self = this;
        var uri = 'build/meta/' + self.buildId;
        var query = query || {};

        if (name == 'build-updated') {
            uri = 'build/data/' + self.buildId;
        }

        var cb = function() {
            $.ajax({
                dataType: "json",
                url: window.PHPCI_URL + uri,
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
            renderOrder = ['build-time', 'build-lines-chart', 'build-warnings-chart', 'build-log'];
        }

        for (var idx in renderOrder) {
            var key = renderOrder[idx];
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

        var container = $('<div></div>').addClass('ui-plugin ' + plugin.css);
        var content = $('<div></div>').attr('id', plugin.id).append(output);
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