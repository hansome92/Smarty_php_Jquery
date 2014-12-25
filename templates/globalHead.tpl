<title>{$title}</title>

<!--    stylesheets    -->
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->

<link rel="stylesheet" type="text/css" href="{$urlPrefix}main.css{$cacheBust}">
<link rel="stylesheet" type="text/css" href="{$urlPrefix}css/ui-lightness/jquery-ui-1.9.0.custom.min.css{$cacheBust}">


<!-- Bootstrap CSS fixes for IE6 -->
<!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script><![endif]-->

<!-- Bootstrap Image Gallery styles -->


<script src="{$urlPrefix}main.js{$cacheBust}" type="text/javascript"></script>


{if $page eq 'profile'}
<script src="{$urlPrefix}profile.js{$cacheBust}" type="text/javascript"></script>
{/if}

<link rel="stylesheet" type="text/css" href="http://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css">
<link rel="stylesheet" type="text/css" href="{$urlPrefix}css/jquery.fileupload-ui.css{$cacheBust}">
{if $page eq 'album'}
<script src="{$urlPrefix}album.js{$cacheBust}" type="text/javascript"></script>
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="{$urlPrefix}/css/albums.css{$cacheBust}" type="text/css" />
<!--[if gte IE 8]><script src="{$urlPrefix}/js/fileup/cors/jquery.xdr-transport.js{$cacheBust}" type="text/javascript"></script><![endif]-->
{/if}
