<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of deletePics
 *
 * @author Administrator
 */
include_once '../include.php';
$answer = array("error"=>0,"photoChanged"=>array());

//print_r($_SESSION);
//print_r($_POST);
if(isset($_SESSION['id']) && isset($_POST['albumId']) && isset($_POST['picList'])){

    include_once '../photoAlbum.php';
    $pa = new photoAlbum();
    $answer['photoChanged'] = $pa->removePhotoFromAlbums($_SESSION['id'], $_POST['albumId'], $_POST['picList']);
} else {
    $answer['error'] = 1;
}
echo json_encode($answer);

?>
