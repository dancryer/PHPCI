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
* Updates the build screen. Called at regular intervals on /build/view/X
*/
function updateBuildView(data)
{
	$('#status').attr('class', 'alert');

	var cls;
	var msg;

	switch(data.status)
	{
		case 0:
			cls = 'alert-info';
			msg = 'This build has not yet started.';
		break;

		case 1:
			cls = 'alert-warning';
			msg = 'This build is in progress.';
		break;

		case 2:
			cls = 'alert-success';
			msg = 'This build was successful!';
		break;

		case 3:
			cls = 'alert-error';
			msg = 'This build has failed.';
		break;
	}

	$('#status').addClass(cls).text(msg);

	if(data.created)
	{
		$('#created').text(data.created);
	}
	else
	{
		$('#created').text('Not created yet.');
	}

	if(data.started)
	{
		$('#started').text(data.started);
	}
	else
	{
		$('#started').text('Not started yet.');
	}

	if(data.finished)
	{
		$('#finished').text(data.finished);
	}
	else
	{
		$('#finished').text('Not finished yet.');
	}

	if(data.plugins)
	{
		$('#plugins').empty();

		for(var plugin in data.plugins)
		{
			var row = $('<tr>').addClass(data.plugins[plugin] ? 'success' : 'error');
			var name = $('<td>').html('<strong>' + plugin + '</strong>');
			var status = $('<td>').text(data.plugins[plugin] ? 'OK' : 'Failed');

			row.append(name);
			row.append(status);
			$('#plugins').append(row);
		}
	}
	else
	{
		var row = $('<tr>');
		var col = $('<td>').attr('colspan', 2).text('No plugins have run yet.');

		row.append(col);
		$('#plugins').empty().append(row);
	}

	$('#log').html(data.log);
}

/**
* Used to initialise the project form:
*/
function setupProjectForm()
{
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
		if(!window.github_app_id || $(this).val() != 'github' || window.github_token) {
			return;
		}
		
		// Show sign in with Github button.
		var el = $('#element-reference');
		var rtn = window.return_url;
		var url = 'https://github.com/login/oauth/authorize?client_id=' + window.github_app_id + '&scope=repo&redirect_uri=' + rtn;
		var btn = $('<a>').addClass('btn btn-inverse').text('Sign in with Github').attr('href', url);

		el.after(btn);
		el.remove();
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