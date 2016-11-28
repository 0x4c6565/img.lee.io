<?php

session_start();

include_once('config.php');

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

function get_guid()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function upload_file($data)
{
    $size = getimagesize($data);
    if (!$size)
    {
        throw new Exception("Invalid image");
    }

    $file_extension = get_image_extension_from_mime($size['mime']);
    $destination_file_guid = get_guid();
    $destination_file_name = $destination_file_guid.".".$file_extension;
    $destination_file_relative_path = "ul/".$destination_file_name;
    $destination_file_path = DOCROOT."/".$destination_file_relative_path;
    $destination_file_url = "https://".$_SERVER['HTTP_HOST']."/".$destination_file_relative_path;

    $destination_file_stream = fopen($destination_file_path, 'w');
    $source_file_stream = fopen($data, 'r');

    pipe_streams($source_file_stream, $destination_file_stream);

    fclose($source_file_stream);
    fclose($destination_file_stream);

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

if (!check_login())
{
        header('HTTP/1.0 401 Unauthorized');
        exit;
}

if (isset($_POST) && !is_null($_POST['data']))
{
        echo upload_file($_POST['data']);
}
