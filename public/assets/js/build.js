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

        this.toggleWidget(content);

        container.append(content);

        $('#plugins').append(container);
    },

    toggleWidget: function($box) {
        var self = this;
        var id = $box.attr('id');
        var $header = $box.find('.box-header');
        var $content = $header.next();

        // Add widget toggler
        var $toggle = $('<i class="box-tools fa pull-right fa-angle-down"></i>');
        if (self.isWidgetHidden(id)) {
            $content.addClass('hidden');
            $toggle.toggleClass('fa-angle-down fa-angle-left');
        }
        $toggle.appendTo($header).click(function() {
            $content.toggleClass('hidden')
            if ($content.hasClass('hidden')) {
                self.hideWidget(id);
            } else {
                self.showWidget(id);
            }
            $(this).toggleClass('fa-angle-down fa-angle-left');
        });
    },

    isWidgetHidden: function(id) {
        var settings = this._getSettings('hidden_widgets');
        return (settings.indexOf(id) != -1);
    },

    hideWidget: function(id) {
        var settings = this._getSettings('hidden_widgets');
        var index = settings.indexOf(id);
        if (index == -1) {
            settings.push(id);
            this._storeSettings('hidden_widgets', settings);
        }
    },

    showWidget: function(id) {
        var settings = this._getSettings('hidden_widgets');
        var index = settings.indexOf(id);
        if (index != -1) {
            settings.splice(index, 1);
            this._storeSettings('hidden_widgets', settings);
        }
    },

    _getSettings: function(setting) {
        var settingsArray = [];
        var settingsString = $.cookie(setting);
        if (settingsString) {
            settingsArray = settingsString.split(',');
        }

        return settingsArray;
    },

    _storeSettings: function(setting, value) {
        $.cookie(setting, value.toString());
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