<?php
include_once '../include.php';
$answer = array("error"=>0,"albumId"=>"");

if(isset($_SESSION['id']) && isset($_POST['name'])){
    include_once '../photoAlbum.php';
    $pa = new photoAlbum();
    $answer['albumId'] = $pa->addAlbum($_SESSION['id'], $_POST['name']);
    if ($answer['albumId'] == -1) {
        $answer['error'] = 1;
    }
} else {
    $answer['error'] = 1;
}
echo json_encode($answer);