<?php
/**
 * Created by JetBrains PhpStorm.
 * User: atlete
 * Date: 07.08.12
 * Time: 2:10
 * To change this template use File | Settings | File Templates.
 */

require_once 'pendingDownload.php';
require_once 'photoAlbum.php';
$pd = new PendingDownload();
$pa = new photoAlbum();
$pendingList = $pd->getPendingList();
foreach($pendingList as $img){
    $img['filename'] = $pa->addPhotoNew($img['userId'],$img['source'],0,$img['height'],$img['width']);
    $pd->uploadImage($img);
    $pd->removePendingElement($img['id']);
}
