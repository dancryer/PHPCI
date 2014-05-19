/**
 * See https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Function/bind
 * for the details of code below
 */
if (!Function.prototype.bind) {
    Function.prototype.bind = function (oThis) {
        if (typeof this !== "function") {
            // closest thing possible to the ECMAScript 5 internal IsCallable function
            throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
        }

        var aArgs = Array.prototype.slice.call(arguments, 1),
            fToBind = this,
            fNOP = function () {
            },
            fBound = function () {
                return fToBind.apply(this instanceof fNOP && oThis
                    ? this
                    : oThis,
                    aArgs.concat(Array.prototype.slice.call(arguments)));
            };

        fNOP.prototype = this.prototype;
        fBound.prototype = new fNOP();

        return fBound;
    };
}

/**
* Used for delete buttons in the system, just to prevent accidental clicks.
*/
function confirmDelete(url, subject, reloadAfter) {

    var dialog = new PHPCIConfirmDialog({
        message: subject + ' will be permanently deleted. Are you sure?',
        confirmBtnCaption: 'Delete',
        /*
         confirm-btn click handler
         */
        confirmed: function (e) {
            var dialog = this;
            e.preventDefault();

            /*
             Call delete URL
             */
            $.ajax({
                url: url,
                'success': function (data) {
                    if (reloadAfter) {
                        dialog.onClose = function () {
                            window.location.reload();
                        };
                    }

                    dialog.showStatusMessage('Successfully deleted!', 1000);
                },
                'error': function (data) {
                    dialog.showStatusMessage('Deletion failed! Server says "' + data.statusText + '"');
                }
            });
        }
    });

    dialog.show();
    return dialog;
}

/**
 * PHPCIConfirmDialog constructor options object
 * @type {{message: string, title: string, confirmBtnCaption: string, cancelBtnCaption: string, confirmed: Function}}
 */
var PHPCIConfirmDialogOptions = {
    message: 'The action will be performed and cannot be undone. Are you sure?',
    title: 'Confirmation Dialog',
    confirmBtnCaption: 'Ok',
    cancelBtnCaption: 'Cancel',
    confirmed: function (e) {
        this.close();
    }
};

var PHPCIConfirmDialog = Class.extend({
    /**
     * @private
     * @var {bool} Determines whether the dialog has been confirmed
     */
    confirmed: false,
    
    /**
     * @param {PHPCIConfirmDialogOptions} options
     */
    init: function (options) {

        options = options ? $.extend(PHPCIConfirmDialogOptions, options) : PHPCIConfirmDialogOptions;

        if (!$('#confirm-dialog').length) {
            /*
             Add the dialog html to a page on first use. No need to have it there before first use.
             */
            $('body').append(
                '<div class="modal fade" id="confirm-dialog">'
                    + '<div class="modal-dialog">'
                    + '<div class="modal-content">'
                    + '<div class="modal-header">'
                    + '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
                    + '<h4 class="modal-title"></h4>'
                    + '</div>'
                    + '<div class="modal-body">'
                    + '<p></p>'
                    + '</div>'
                    + '<div class="modal-footer">'
                    + '<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>'
                    + '<button type="button" class="btn btn-primary"></button>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
            );
        }

        /*
         Define dialog controls
         */
        this.$dialog = $('#confirm-dialog');
        this.$cancelBtn = this.$dialog.find('div.modal-footer button.btn-default');
        this.$confirmBtn = this.$dialog.find('div.modal-footer button.btn-primary');
        this.$title = this.$dialog.find('h4.modal-title');
        this.$body = this.$dialog.find('div.modal-body');

        /*
         Initialize its values
         */
        this.$title.html(options.title ? options.title : PHPCIConfirmDialogOptions.title);
        this.$body.html(options.message ? options.message : PHPCIConfirmDialogOptions.message);
        this.$confirmBtn.html(
            options.confirmBtnCaption ? options.confirmBtnCaption : PHPCIConfirmDialogOptions.confirmBtnCaption
        );

        this.$cancelBtn.html(
            options.cancelBtnCaption ? options.cancelBtnCaption : PHPCIConfirmDialogOptions.cancelBtnCaption
        );

        /*
         Events
         */
        this.confirmBtnClick = options.confirmed;

        /*
         Re-bind handlers
         */
        this.$confirmBtn.unbind('click');
        this.$confirmBtn.click(this.onConfirm.bind(this));

        this.$confirmBtn.unbind('hidden.bs.modal');

        /*
         Bind the close event of the dialog to the set of onClose* methods
         */
        this.$dialog.on('hidden.bs.modal', function () {this.onClose()}.bind(this));
        this.$dialog.on('hidden.bs.modal', function () {
            if (this.confirmed) {
                this.onCloseConfirmed();
            } else {
                this.onCloseCanceled();
            }
        }.bind(this));

        /*
        Restore state if was changed previously
         */
        this.$cancelBtn.show();
        this.$confirmBtn.show();
        this.confirmed = false;
    },

    /**
     * Show dialog
     */
    show: function () {
        this.$dialog.modal('show');
    },

    /**
     * Hide dialog
     */
    close: function () {
        this.$dialog.modal('hide');
    },

    onConfirm: function (e) {
        this.confirmed = true;
        $(this).attr('disabled', 'disabled');
        this.confirmBtnClick(e);
    },

    /**
     * Called only when confirmed dialog was closed
     */
    onCloseConfirmed: function () {},

    /**
     * Called only when canceled dialog was closed
     */
    onCloseCanceled: function () {},

    /**
     * Called always when the dialog was closed
     */
    onClose: function () {},

    showStatusMessage: function (message, closeTimeout) {
        this.$confirmBtn.hide();
        this.$cancelBtn.html('Close');

        /*
        Status message
         */
        this.$body.html(message);

        if (closeTimeout) {
            window.setTimeout(function () {
                /*
                 Hide the dialog
                 */
                this.close();
            }.bind(this), closeTimeout);
        }
    }
});

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
            $.getJSON(window.PHPCI_URL + uri, query, function(data) {
                $(window).trigger({type: name, queryData: data});
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
            handle: '.panel-title',
            connectWith: '#plugins',
            update: self.storePluginOrder
        });

        $(window).trigger({type: 'build-updated', queryData: self.buildData});
    },

    renderPlugin: function(plugin) {
        var output = plugin.render();

        if (!plugin.box) {
            output = $('<div class="panel-body"></div>').append(output);
        }

        var container = $('<div></div>').addClass('ui-plugin ' + plugin.css);
        var content = $('<div></div>').attr('id', plugin.id).append(output);
        content.addClass('panel panel-default');

        if(plugin.title != undefined) {
            $('<a>').attr({'class':'list-group-item', 'href':'#'+plugin.id}).html(plugin.title).appendTo($('#anchorPlugins'));
        }

        if (plugin.title) {
            content.prepend('<div class="panel-heading"><h3 class="panel-title">'+plugin.title+'</h3></div>');
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
