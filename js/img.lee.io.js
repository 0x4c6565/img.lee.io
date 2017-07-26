

$(function() {

    var pasteCatcher = document.getElementById('paste-catcher');

    // Bring into focus
    pasteCatcher.focus();

    // Add the paste event listener
    window.addEventListener("paste", pasteHandler);
       
    function pasteHandler(e) {

        // Check whether clipboardData is supported
        if (e.clipboardData.items != null) {
            var items = e.clipboardData.items;
            // Loop through all items, looking for any kind of image
            for (var i = 0; i < items.length; i++) {

                // We need to represent the image as a file,
                var file = items[i].getAsFile();
                uploadFile(file);
            }
        } else {

            // If we can't handle clipboard data directly (Firefox), 
            // we need to read what was pasted from the contenteditable element
            setTimeout(uploadPasteCatcher, 20);
        }
    }

    function uploadFile(file) {
        if (file != null && file.type != null && file.type.indexOf("image") !== -1) {
            var reader = new FileReader();
            reader.onload = function(event){
                uploadImageToServer(event.target.result);
            };
            reader.readAsDataURL(file);
        }
    }
       
    function uploadPasteCatcher() {
        // Store the pasted content in a variable
        var child = pasteCatcher.childNodes[0];
        // Clear the inner html to make sure we're always
        // getting the latest inserted content
        pasteCatcher.innerHTML = "";

        if (child) {
            // If the user pastes an image, the src attribute
            // will represent the image as a base64 encoded string.
            if (child.tagName === "IMG") {
                uploadImageToServer(child.src);
            }
        }
    }

    function uploadImageToServer(base64) {
        console.log('Starting upload');
        $.ajax({
            url: '/upload.php',
            timeout: 90000,
            type: 'POST',
            data: {
                data: base64,
            },
            error: function(jqXHR, textStatus) {
                var msg = 'Upload error: (' + jqXHR.status + ' ' + jqXHR.statusText + ')';
                console.log('Failed upload: '+msg);
                addAlertError(msg);
            },
            success: function(data, textStatus, jqXHR) {
                console.log('Upload complete: '+data);
                addAlertUploadSuccess(data);
            }
        });
    }




    // Handle result

    function selectText(elem) {
        var select = window.getSelection();
        select.removeAllRanges();

        var range = document.createRange();
        range.selectNodeContents(elem);

        select.addRange(range);
    }

    document.addEventListener('click', function(e) {    
        if (e.target.className == 'upload_url') {
            e.preventDefault();
            selectText(e.target);
        } else {
            pasteCatcher.focus(); 
        }
    }, false);



    // Drag and Drop


    var uploadBox = document.getElementById('upload-box');

    uploadBox.ondragover = function(e) {
        return false;
    };

    uploadBox.ondrop = function(e) {
        e.preventDefault();  
        e.stopPropagation();
        if (e.dataTransfer.files) {
            var files = e.dataTransfer.files;
            // Loop through all items, looking for any kind of image
            for (var i = 0; i < files.length; i++) {
                uploadFile(files[i]);
            }
        }
    };



    // File upload

    var imageInput = document.getElementById('image-input');
    var imageInputLabel = document.getElementById('image-input-label');

    imageInputLabel.onclick = function(e) {
        console.log('test');
        imageInput.click();
    };

    imageInput.onchange = function(e) {
        console.log('uploading');
        if (this.files) {
            for (var i = 0; i < this.files.length; i++) {
                uploadFile(this.files[i]);
            }
        }
    };
    


    // Alerts

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

});



