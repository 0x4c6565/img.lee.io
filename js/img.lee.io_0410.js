

$(function() {

    // Paste

    var pasteCatcher = document.getElementById('paste-catcher');

    // Bring into focus
    pasteCatcher.focus();

    // Bring paste capture into focus on click
    document.addEventListener('click', function(e) {
        pasteCatcher.focus();
    }, false);

    // Add the paste event listener
    window.addEventListener("paste", pasteHandler);
       
    function pasteHandler(e) {

        // Check whether clipboardData is supported
        if (e.clipboardData.items != null) {
            var items = e.clipboardData.items;
            for (var i = 0; i < items.length; i++) {
                var file = items[i].getAsFile();
                uploadFile(file);
            }
        } else {

            // If we can't handle clipboard data directly (Firefox), 
            // we need to read what was pasted into the contenteditable element
            uploadPasteCatcher();
            //setTimeout(uploadPasteCatcher, 20);
        }
    }
       
    
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

    uploadBox.onclick = function(e) {
        imageInput.click();
    };

    imageInput.onchange = function(e) {
        if (this.files) {
            for (var i = 0; i < this.files.length; i++) {
                uploadFile(this.files[i]);
            }
        }
    };


    // Upload

    function uploadPasteCatcher() {
        var child = pasteCatcher.childNodes[0];
        pasteCatcher.innerHTML = "";

        if (child) {
            if (child.tagName === "IMG") {
                uploadImageToServer(child.src);
            }
        }
    }

    function uploadFile(file) {
        if (file != null && file.type != null && file.type.indexOf("image") !== -1) {
            var reader = new FileReader();
            reader.onload = function(event){
                uploadImageToServer(event.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            toastr.error('Invalid image', null, {timeOut: 3000, extendedTimeOut: 1000});
        }
    }

    function uploadImageToServer(base64) {
        console.log('Upload starting');
        toastr.info('Uploading', null, {timeOut: 2000, extendedTimeOut: 1000});
        $.ajax({
            url: '/upload.php',
            timeout: 90000,
            type: 'POST',
            data: {
                data: base64,
            },
            error: function(jqXHR, textStatus) {
                var msg = jqXHR.status + ' ' + jqXHR.statusText;
                console.log('Upload failed: '+msg);
                toastr.error(msg, null, {timeOut: 3000, extendedTimeOut: 1000});
            },
            success: function(data, textStatus, jqXHR) {
                console.log('Upload complete: '+data);
                toastr.success('<span class="upload-url">'+data+'</span><a target="_blank" href="'+data+'" class="thumbnail"><img src="'+data+'" alt="..."></a>', null, {closeButton: true, timeOut: 0, extendedTimeOut: 0, tapToDismiss: false}).css("min-width","430px");
            }
        });
    }


    // Misc

    function selectText(elem) {
        var select = window.getSelection();
        select.removeAllRanges();

        var range = document.createRange();
        range.selectNodeContents(elem);

        select.addRange(range);
    }

    document.addEventListener('click', function(e) {
        if (e.target.className == 'upload-url') {
            e.preventDefault();
            selectText(e.target);
        } else {
            pasteCatcher.focus();
        }
    }, false);

});



