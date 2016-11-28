var pasteCatcher;
function initialisePaste() {
	$(function() {
		pasteCatcher = document.createElement("div");

		// Create content editable div to capture none clipboardData items
		pasteCatcher.setAttribute("contenteditable", "");

		// Hide
		pasteCatcher.style = "left: -9999px; position: absolute; opacity: 0;";

        // Add to body
		document.body.appendChild(pasteCatcher);

		// Bring into focus
		pasteCatcher.focus();

        // Ensure div remains in focus on click
		document.addEventListener("click", function(e) { if (e.target.className != 'upload_url') { pasteCatcher.focus(); } });

		// Add the paste event listener
		window.addEventListener("paste", pasteHandler);
	});	
}
 
function pasteHandler(e) {
	// Check whether clipboardData is supported
	if (e.clipboardData.items != null) {
		// Get items from clipboard
		var items = e.clipboardData.items;
		if (items) {
			// Loop through all items, looking for any kind of image
			for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf("image") !== -1) {
				   // We need to represent the image as a file,
				   var blob = items[i].getAsFile();

				    var reader = new FileReader();
				    reader.onload = function(event){
				        uploadImageToServer(event.target.result);
				    };
				    reader.readAsDataURL(blob);
				}
			}
		}
   // If we can't handle clipboard data directly (Firefox), 
   // we need to read what was pasted from the contenteditable element
   } else {
		setTimeout(checkInput, 20);
   }
}
 
/* Parse the input in the paste catcher element */
function checkInput() {
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

function selectText(elem) {
    var select = window.getSelection();
    var range  = document.createRange();

    range.selectNodeContents(elem);
    select.addRange(range);
}

document.addEventListener('click', function (e) {    
	if (e.target.className == 'upload_url') {
		selectText(e.target);
	}
}, false);
