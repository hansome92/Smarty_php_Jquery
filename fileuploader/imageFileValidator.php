<?php
    /**
     * File: imageFileValidator.php
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */

    class imageFileValidator {
        protected $extensions = array( 'png',
                                       'gif',
                                       'jpeg',
                                       'jpg',
        );

        function __construct() {

        }

        function validateFile( $file, $currentFileLocation ) {
            $ext = $this->checkExtension( $file['name'] );
            if( !$ext['valid'] ) {
                return $ext;
            }
            $err = array( 'error' => 'The image file is invalid or corrupt.', 'valid' => FALSE );
            try {
                $gdres = @$this->loadIntoGd( $ext['ext'], $currentFileLocation );
                if( !$gdres ) {
                    return $err;
                }
                imagedestroy( $gdres );
            } catch( Exception $e ) {
                return $err;
            }

            return array( 'valid' => TRUE );
        }

        function loadIntoGd( $ext, $currentFileLocation ) {
            switch( strtolower( $ext ) ) {
                case 'png':
                    return imagecreatefrompng( $currentFileLocation );
                    break;
                case 'gif':
                    return imagecreatefromgif( $currentFileLocation );
                    break;
                case 'jpeg':
                    return imagecreatefromjpeg( $currentFileLocation );
                    break;
                case 'jpg':
                    return imagecreatefromjpeg( $currentFileLocation );
                    break;
                default:
                    echo 'Error with file validation- problem with the file type!';
                    return NULL;
                    break;
            }
        }

        function cropPhoto( $x1, $y1, $x2, $y2, $src, $target ) {
            $err = array( 'error' => 'The image file path is invalid, please let an admin know about this error.', 'valid' => FALSE );
            try {
                if( !file_exists( $src ) ) {
                    return $err;
                }
                $ext = $this->getExtension( $src );
                $image = @$this->loadIntoGd( $ext, $src );
                if( !$image ) {
                    return $err;
                }
                $newImage = ImageCreateTrueColor( $x2 - $x1, $y2 - $y1 );
                imagealphablending( $newImage, FALSE );
                imagesavealpha( $newImage, TRUE );
                imagecopyresampled( $newImage, $image, 0, 0, $x1, $y1, $x2 - $x1, $y2 - $y1, $x2 - $x1, $y2 - $y1 );
                $this->outputFromGd( $ext, $newImage, $target );
                imagedestroy( $newImage );
                imagedestroy( $image );
                $err['valid'] = TRUE;
                $err['error'] = 'none';
                return $err;
            } catch( Exception $e ) {
                $err['em'] = $e->getMessage();
                $err['ec'] = $e->getCode();
                return $err;
            }
        }

        function resizePhoto( $path, $xmax, $ymax, $outputFiletype = NULL ) {
            $ext = $this->getExtension( $path );
            $image = $this->loadIntoGd( $ext, $path );
            $imagex = imageSX( $image );
            $imagey = imageSY( $image );
            $newx = 100;
            $newy = 100;
            if( $imagex <= $xmax && $imagey <= $ymax ) {
                $newx = $imagex;
                $newy = $imagey;
            } elseif( $imagex > $imagey ) {
                $newx = $xmax;
                $newy = $imagey * ( $ymax / $imagex );
            } elseif( $imagex < $imagey ) {
                $newx = $imagex * ( $xmax / $imagey );
                $newy = $ymax;
            } elseif( $imagex == $imagey ) {
                $newx = $xmax;
                $newy = $ymax;
            }
            $newImage = ImageCreateTrueColor( $newx, $newy );
            imagealphablending( $newImage, FALSE );
            imagesavealpha( $newImage, TRUE );
            imagecopyresampled( $newImage, $image, 0, 0, 0, 0, $newx, $newy, $imagex, $imagey );
            $ext = !is_null( $outputFiletype ) ? $outputFiletype : $ext;
            $this->outputFromGd( $ext, $newImage, $path );
            imagedestroy( $newImage );
            imagedestroy( $image );
        }

        function outputFromGd( $ext, $image, $path ) {
            switch( strtolower( $ext ) ) {
                case 'png':
                    return imagepng( $image, $path, 1 );
                    break;
                case 'gif':
                    return imagegif( $image, $path );
                    break;
                case 'jpeg':
                    return imagejpeg( $image, $path, 100 );
                    break;
                case 'jpg':
                    return imagejpeg( $image, $path, 100 );
                    break;
                default:
                    echo 'Error with file validation- how did you get here?';
                    return NULL;
                    break;
            }
        }

        function getExtension( $name ) {
            return pathinfo( $name, PATHINFO_EXTENSION );
        }

        function checkExtension( $name ) {
            $ext = $this->getExtension( $name );
            $res = array( 'ext' => $ext, 'valid' => FALSE );
            if( $this->inArray( trim( strtolower( $ext ) ), $this->extensions ) ) {
                $res['valid'] = TRUE;
                return $res;
            }
            $res['error'] = 'Not a supported image extension: .'.$ext;
            return $res;
        }


        function inArray( $string, $array ) {
            foreach( $array as $i => $val ) {
                if( $string == $val ) {
                    return TRUE;
                    break;
                }
            }
            return FALSE;
        }
    }