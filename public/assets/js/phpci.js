/**
* Used for delete buttons in the system, just to prevent accidental clicks.
*/
function confirmDelete(url)
{
	if(confirm('Are you sure you want to delete this?'))
	{
		window.location.href = url;
	}
	else
	{
		return false;
	}
}

/**
* Used to initialise the project form:
*/
function setupProjectForm()
{
    $('.github-container').hide();

	$('#element-reference').change(function()
	{
		var el	= $(this);
		var val	= el.val();

		var acceptable = {
			'github_ssh': /git\@github\.com\:([a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+)\.git/,
			'github_git': /git\:\/\/github.com\/([a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+)\.git/,
			'github_http': /https\:\/\/github\.com\/([a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+)(\.git)?/,
			'bb_ssh': /git\@bitbucket\.org\:([a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+)\.git/,
			'bb_http': /https\:\/\/[a-zA-Z0-9_\-]+\@bitbucket.org\/([a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+)\.git/,
			'bb_anon': /https\:\/\/bitbucket.org\/([a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+)(\.git)?/
		};

		for(var i in acceptable) {
			if(val.match(acceptable[i])) {
				el.val(val.replace(acceptable[i], '$1'));
			}
		}
	});

	$('#element-type').change(function()
	{
        if ($(this).val() == 'github') {
            $('#loading').show();

            $.getJSON(window.PHPCI_URL + 'project/github-repositories', function (data) {
                $('#loading').hide();

                if (data.repos) {
                    $('#element-github').empty();

                    for (var i in data.repos) {
                        var name = data.repos[i];
                        $('#element-github').append($('<option></option>').text(name).val(name));
                    }

                    $('.github-container').slideDown();
                }
            });
        } else {
            $('.github-container').slideUp();
        }
	});

	$('#element-github').change(function()
	{
		var val = $('#element-github').val();

		if(val != 'choose') {
			$('#element-type').val('github');
			$('#element-reference').val(val);

			$('label[for=element-reference]').hide();
			$('label[for=element-type]').hide();
			$('#element-reference').hide();
			$('#element-type').hide();
			$('#element-token').val(window.github_token);
			$('#element-title').val(val);
		}
		else {
			$('label[for=element-reference]').show();
			$('label[for=element-type]').show();
			$('#element-reference').show();
			$('#element-type').show();
			$('#element-reference').val('');
			$('#element-token').val('');
		}
	});
}

var PHPCIObject = Class.extend({
    buildId: null,
    plugins: {},
    observers: {},
    buildData: {},
    queries: {},
    updateInterval: null,

    init: function(build) {
        this.buildId = build;
        this.registerQuery('build-updated', 10);
    },

    registerQuery: function(name, seconds, query) {
        var self = this;
        var uri = 'build/meta/' + self.buildId;
        var query = query || {};

        if (name == 'build-updated') {
            uri = 'build/data/' + self.buildId;
        }

        var cb = function() {
            $.getJSON(window.PHPCI_URL + uri, query, function(data) {
                $(window).trigger({type: name, queryData: data});
            });
        };

        if (seconds != -1) {
            setInterval(cb, seconds * 1000);
        }

        return cb;
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
            handle: '.title',
            connectWith: '#plugins',
            update: self.storePluginOrder
        });

        $(window).trigger({type: 'build-updated', queryData: self.buildData});
    },

    renderPlugin: function(plugin) {
        var output = $('<div></div>').addClass('box-content').append(plugin.render());
        var container = $('<div></div>').addClass('ui-plugin ' + plugin.css);
        var content = $('<div></div>').attr('id', plugin.id).append(output);

        if (plugin.box) {
            content.addClass('box');
        }

        if (plugin.title) {
            content.prepend('<h3 class="title">'+plugin.title+'</h3>');
        }

        content.append(output);
        container.append(content);

        $('#plugins').append(container);
    },

    UiPlugin: Class.extend({
        id: null,
        css: 'col-lg-4 col-md-6 col-sm-12 col-xs-12',
        box: true,

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