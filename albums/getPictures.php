<?php
/**
 * Created by JetBrains PhpStorm.
 * User: atlete
 * Date: 08.08.12
 * Time: 12:23
 */
include_once '../include.php';
$answer = array("error"=>0,"picList"=>array());
if(isset($_SESSION['id']) && isset($_POST['albumId'])){
    include_once '../photoAlbum.php';
    $pa = new photoAlbum();
    $answer['picList'] = $pa->getPhotosByAlbumId($_POST['albumId'], $_SESSION['id']);
} else {
    $answer['error'] = 1;
}
echo json_encode($answer);