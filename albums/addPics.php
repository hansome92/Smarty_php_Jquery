<?php
/**
 * Created by JetBrains PhpStorm.
 * User: atlete
 * Date: 09.08.12
 * Time: 8:58
 */
include_once '../include.php';
$answer = array("error"=>0,"photoChanged"=>array());

//print_r($_SESSION);
//print_r($_POST);
if(isset($_SESSION['id']) && isset($_POST['albumId']) && isset($_POST['picList'])){

    include_once '../photoAlbum.php';
    $pa = new photoAlbum();
    $answer['photoChanged'] = $pa->movePhotoToAlbum($_SESSION['id'], $_POST['albumId'], $_POST['picList']);
} else {
    $answer['error'] = 1;
}
echo json_encode($answer);