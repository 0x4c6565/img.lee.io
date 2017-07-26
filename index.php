<!DOCTYPE html>
<html>
<head>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/img.lee.io.css" rel="stylesheet">
<link href="/css/font-awesome.min.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/img.lee.io.js"></script>
</head>
<body>
    <div id="paste-catcher" contenteditable></div>


    <div class="container">
            <div id="upload-box" class="upload-box">
                <i class="fa fa-upload" aria-hidden="true"></i>
                <input type="file" name="images[]" id="image-input" class="image-input" multiple/>

                <p><label id="image-input-label" class="image-input-label">Paste, drag+drop or click here</label></p>
            </div>
    </div>
    <div class="col-md-10 col-md-offset-1 clear-top">
        <div id="messages">
        </div>
    </div>

</body>
</html>
