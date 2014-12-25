<?php

    class fontLister {
        private $path, $directoryIterator, $fontFamilyArr, $fontPathArr;

        function __construct( $path = "." ) {
            $this->path = $path;
            $this->directoryIterator = new DirectoryIterator($path);
            $this->populateFontArrays();
        }

        function makeCssLinks() {
            foreach( $this->fontPathArr as $fontPath ) {
                ?>
            <link rel="stylesheet" type="text/css" href="/elance/trinketlily/<?php echo $fontPath ?>/stylesheet.css">
            <?php
            }
        }

        function makeFontDemo() {

            foreach( $this->fontFamilyArr as $font ) {
                ?>
            <div style="padding-bottom:5px;font-family:<?php echo $font; ?>;">  <?php echo trim($font, '\''); ?> </div>
            <?php
            }
        }

        function populateFontArrays() {
            $this->fontFamilyArr = Array();
            $this->fontPathArr = Array();
            foreach( $this->directoryIterator as $file ) {
                if( $file->isDot() ) {
                    continue;
                }
                $possiblePath = $this->path.'/'.$file->getFilename();
                if( is_dir($possiblePath) ) {
                    $this->fontPathArr[] = $possiblePath;
                    $cssFile = file_get_contents($this->path.'/'.$file->getFilename().'/stylesheet.css');
                    preg_match_all('/font\-family.*?;/i', $cssFile, $rawMatchesArr);
                    foreach( $rawMatchesArr as $rawMatches ) {
                        foreach( $rawMatches as $rawMatch ) {
                            preg_match("/'.*?'/i", $rawMatch, $matches);
                            $this->fontFamilyArr[] = $matches[0];
                        }
                    }
                }
            }
        }
    }

?>