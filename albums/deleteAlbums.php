<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../include.php';
$answer = array("error"=>0,"albumChanged"=>array());

//print_r($_SESSION);
//print_r($_POST);
if(isset($_SESSION['id']) &&  isset($_POST['albumList'])){

    include_once '../photoAlbum.php';
    $pa = new photoAlbum();
    $answer['albumChanged'] = $pa->removeAlbumFromAlbums($_SESSION['id'], $_POST['albumList']);
} else {
    $answer['error'] = 1;
}
echo json_encode($answer);

?>
