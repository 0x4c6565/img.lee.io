<?php

include_once('config.php');
include_once('class/image.class.php');
include_once('../../globals/class/db.class.php');
include_once('../../globals/class/uuid.class.php');

function get_image_extension_from_mime($mime)
{
    switch($mime)
    {
        case 'image/png':
            return 'png';
        case 'image/jpeg':
            return 'jpg';
        case 'image/gif':
            return 'gif';
        default:
            throw new Exception("Invalid mime type [{$mime}]");
    }
}

function upload_file($data)
{
    $size = getimagesize($data);
    if (!$size)
    {
        throw new Exception("Invalid image");
    }

    $file_extension = get_image_extension_from_mime($size['mime']);


    $db = new db(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);

    $image = new image($db);
    $image->filename = uuid::new_uuid().".".$file_extension;
    $image->timestamp = time();
    $image->expire_timestamp = (is_numeric($_GET['expires']) && $_GET['expires'] != 0) ? time()+$_GET['expires'] : 0;
    $image->status = image::STATUS_NEW;
    $image->insert();

    $destination_file_url = "https://".$_SERVER['HTTP_HOST']."/".$image->filename;

    $destination_file_stream = fopen($image->get_filepath(), 'w');
    $source_file_stream = fopen($data, 'r');

    pipe_streams($source_file_stream, $destination_file_stream);

    fclose($source_file_stream);
    fclose($destination_file_stream);

    $image->set_status(image::STATUS_COMPLETE);


    return $destination_file_url;
}

function pipe_streams($in, $out)
{
    $size = 0;
    while (!feof($in))
    {
         $size += fwrite($out,fread($in,8192));
    }

    return $size;
}

try
{
    if (!isset($_POST) || is_null($_POST['data']))
    {
        throw new Exception("Missing data");
    }

    echo upload_file($_POST['data']);
}
catch (Exception $ex)
{
    http_response_code(500);
    echo "Failed to upload image: {$ex->getMessage()}";
}
