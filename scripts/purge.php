<?php
error_reporting(E_ERROR);

include_once(__DIR__.'/../config.php');
include_once(__DIR__.'/../class/image.class.php');
include_once(__DIR__.'/../../../globals/class/db.class.php');

$db = new db(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
$expired_images = image::get_expired($db);
if (count($expired_images) > 0)
{
    foreach ($expired_images as $expired_image)
    {
        $expired_image->burn();
    }
}
