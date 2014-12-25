<?php
/**
 * Created by JetBrains PhpStorm.
 * User: atlete
 * Date: 07.08.12
 * Time: 14:41
 */
include_once '../include.php';
$answer = array("error"=>0,"freePhotoList"=>array());
if(isset($_SESSION['id'])){
    include_once '../photoAlbum.php';
    $pa = new photoAlbum();
    $answer['freePhotoList'] = $pa->getCurrentUsersUnfiledPhotos();
} else {
    $answer['error'] = 1;
}
echo json_encode($answer);