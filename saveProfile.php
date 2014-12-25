<?php
    require_once 'include.php';
    require_once 'access.php';

    $access = new access();

    $uid = $access->getCurrentUsersId();
    if( !is_null( $uid ) && strlen( $uid ) > 5 ) {
        if( isset( $_POST['section'] ) && ( isset( $_POST['content'] ) || isset( $_FILES['profilePic'] ) ) ) {
            $section = (string)$_POST['section'];
            $content = NULL;
            if( isset( $_POST['content'] ) ) {
                parse_str( $_POST['content'], $content );
            }

            require_once 'book.php';
            $book = new book();

            if( is_null( $book->getCurrentUsersId() ) ) {
                die( 'You are not logged in!' );
            }

            $defaultSuccess = TRUE;
            switch( $section ) {
                case 'profilePicture':
                    $defaultSuccess = FALSE;
                    if( isset( $_POST['flashUploader'] ) && $_POST['flashUploader'] == 'true' && !empty( $_FILES ) ) {
                        require_once 'fileuploader/imageFileValidator.php';
                        $tempFile = $_FILES['profilePic']['tmp_name'];
                        $ds = DIRECTORY_SEPARATOR;
                        $targetPath = realpath( dirname( __FILE__ ) ).$ds.'userpics'.$ds.$_SESSION['id'].$ds;

                        if( !is_dir( $targetPath ) ) {
                            mkdir( $targetPath, 0777 );
                        }

                        $validator = new imageFileValidator();
                        $res = $validator->validateFile( array( 'name' => $_FILES['profilePic']['name'] ), $tempFile );

                        if( $res['valid'] ) {
                            move_uploaded_file( $tempFile, $targetPath.$_FILES['profilePic']['name'] );
                            $validator->resizePhoto( $targetPath.$_FILES['profilePic']['name'], 200, 200, 'png' );
                            rename( $targetPath.$_FILES['profilePic']['name'], $targetPath.'profile.png' );
                            echo 'Successfully changed profile picture.';
                        } else {
                            echo $res['error'];
                        }
                    } else {
                        echo json_encode( 'Error uploading profile picture. Try using a modern browser.' );
                    }
                    //exit(0);
                    break;

                case 'accountInformation':
                    $book->updateCurrentUserProfile( $content );
                    break;

                case 'scrapbooks':
                    $user = $book->getCurrentUsersArray();
                    if( isset( $user['scrapbooks'] ) && sizeof( $user['scrapbooks'] ) > 0 ) {
                        //user has at least one scrapbook
                        if( !( isset( $user['paidUser'] ) && $user['paidUser'] == TRUE ) ) {
                            //user is NOT A PAID ACCOUNT
                            die( json_encode( array( 'error' => 1, 'message' => 'Free accounts are limited to only one scrapbook.'."\n\n".
                                'Please upgrade to a paid account to make additional scrapbooks!' ) ) );
                        }
                    }
                    $scraplook = isset( $_POST['scraplook'] ) ? urldecode( $_POST['scraplook'] ) : 'default';
                    $bookname = sanitize( $_POST['content'] );
                    if( is_null( $book->findCurrentUsersBookByName( $bookname ) ) ) {
                        $bookId = $book->addNewCurrentUserScrapbook( $bookname, $scraplook );
                        $scraplookCoverUrl = $book->scraplooks->getCoverUrlByName( $scraplook );
                        die( json_encode( array( 'error' => 0, 'bookid' => $bookId, 'scraplookCoverUrl' => $scraplookCoverUrl ) ) );
                    } else {
                        die( json_encode( array( 'error' => 1, 'message' => 'You must enter a unique scrapbook name.' ) ) );
                    }
                    break;
                default:
                    echo json_encode( 'Invalid command.' );
                    break;
            }
            if( $defaultSuccess ) {
                echo json_encode( 'Successfully saved your information.' );
            }
        } else {
            echo json_encode( 'Missing required parameters!' );
        }
    } else {
        echo json_encode( 'You are not logged in!' );
    }

?>