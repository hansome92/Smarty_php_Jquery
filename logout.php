<?php
    require_once 'include.php';
    require_once 'login/facebook.php';
    $facebook = new Facebook(array( 'appId'     => '226296247479734',
                                    'secret'    => '43eee92137eb3e14c952568da9faacbe'
                             ));

    session_unset();
    session_destroy();
    $facebook->destroySession();
    header('Location: '.URL_PREFIX);

?>