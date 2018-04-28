<!DOCTYPE html>
<html>
<head>
<title>img.lee.io</title>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<link href="css/img.lee.io_06042018.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/toastr.min.css" rel="stylesheet">
</head>
<body>
    <div id="paste-catcher" contenteditable></div>


    <div id="upload-box">
        <div class="icon">
            <i class="fa fa-upload" aria-hidden="true"></i>
        </div>
        <div class="content">
            <input type="file" name="images[]" id="image-input" class="image-input" multiple/>
            <p><label id="image-input-label" class="image-input-label">Paste, drag+drop or click here</label></p>
                
        </div>
        <div id="expire-container">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                    <select id="select-expire" class="form-control clickable">
                        <option value="0" class="clickable">Never</option>
                        <option value="600" class="clickable">10 min</option>
                        <option value="1800" class="clickable">30 min</option>
                        <option value="3600" class="clickable">1 hour</option>
                        <option value="21600" class="clickable">6 hours</option>
                        <option value="86400" class="clickable">24 hours</option>
                        <option value="604800" class="clickable" selected>1 week</option>
                        <option value="2419200" class="clickable">1 month</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="messages">
    </div>

<script src="js/jquery.min.js"></script>
<script src="js/toastr.min.js"></script>
<script src="js/img.lee.io_06042018.js"></script>

</body>
</html>
