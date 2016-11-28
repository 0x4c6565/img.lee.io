function addAlertUploadSuccess(url) {
	var message = '<span class="upload_url">' + url + '</span> <a href="'+url+'" class="thumbnail"><img src="'+url+'" alt="..."></a>';
	addAlert('success', message);
}

function addAlertError(message) {
	addAlert('danger', message);
}

function addAlert(type, message) {
    $('#messages').append(
        '<div class="alert alert-' + type + ' text-center fade in">' +
            '<span class="close" data-dismiss="alert">&times;</span>' + message + '</div>');
}