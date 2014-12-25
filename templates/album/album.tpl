{literal}
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
        <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
        <td>
            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="bar" style="width:0%;"></div>
            </div>
        </td>
        <td class="start">{% if (!o.options.autoUpload) { %}
            <button class="btn btn-primary">
                <i class="icon-upload icon-white"></i>
                <span>{%=locale.fileupload.start%}</span>
            </button>
                          {% } %}
        </td>
        {% } else { %}
        <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
                           {% } %}
        </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
        <td></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
        <td class="preview">{% if (file.url) { %}
            <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img class="preview-img" src="{%=file.url%}"></a>
                            {% } %}
        </td>
        <td class="name">
            <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
        </td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        <td>&nbsp;</td>
        <td class="" colspan="2">
            <span class="btn btn-success"> Success </span>
        </td>
        {% } %}
    </tr>
    {% }
    %}
</script>

{/literal}

{album}
{if isset($userId) && $userId!=''}
<div id="albums">
    <fieldset>
        <img src="{$urlPrefix}images/uploadPhotosHeader.png" id="uploadPhotosHeader">

        <div style="clear:both;"></div>

        <div class="add-album">
            <div class="newAlbumImage">
                <img src="{$urlPrefix}images/unfiledPicturesAlbum.png" />
            </div>
            <label for="albumName">create new album: </label>
            <input type="text" id="albumName" value="" />
            <button class="btn btn-success" onclick="addAlbum();">
                add
            </button>
        </div>

        <div id="addAlbums">
            <!--div class="album-title"><span>My Albums</span></div><hr-->

            <div class="unfile-album">
                <div class="each-album" id="album_default" albumid="default">
                    <div class="album-img-area">
                        <img class="album-img select-album" albumid="default" alt="Unfiled Pictures" src="{$urlPrefix}images/unfiledPicturesAlbum.png" />
                    </div>
                    <div class="album-name ">
                        <span>Unfiled Pictures</span>
                    </div>
                </div>
            </div>

            <div id="albumForm">
                {foreach from=$albums item=album}
                    <div class="each-album" id="album_{$album.albumid}" albumId="{$album.albumid}">
                        <div class="delete-button-album">
                            <a class="remove" albumId="{$album.albumid}">&times;</a>
                        </div>
                        <div class="album-img-area">
                            {if strlen($album.filename) < 3}
                                <img class="album-img" albumId="{$album.albumid}" alt="{$album.name}" src="{$urlPrefix}images/unfiledPicturesAlbum.png" />
                                {else}
                                <img class="album-img" albumId="{$album.albumid}" alt="{$album.name}" src="{$urlPrefix}userpics/{$userId}/{$album.filename}" />
                            {/if}
                        </div>
                        <div class="album-name ">
                            <span>{$album.name}</span>
                        </div>
                    </div>
                {/foreach}
            </div>

        </div>

        <div id="freePhoto">
            <div class="free-photo-name">
                <span><!--Photos--></span>
            </div>
            <hr />
            <div id="thumbsWrapper">
                <div id="freePhotoList">
                </div>
            </div>
        </div>
        <!-- end freePhoto -->
    </fieldset>
    <div style="clear:both;">
    </div>

    <hr>

    <fieldset id="uploadPhoto">
        <legend><!--Upload Photos--></legend>
        <div id="uploadDialog">
            <form id="fileupload" action="{$urlPrefix}fileuploader/" method="POST" enctype="multipart/form-data">
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row fileupload-buttonbar">
                    <div class="span10">
                        <!-- The fileinput-button span is used to style the file input field as a button -->
                                        <span class="btn btn-success fileinput-button">
                                            <!--img src="{$urlPrefix}images/uploadPhotosButton.png" alt="Upload photos from your computer"-->
                                            <i class="icon-plus icon-white"></i>
                                            <span>add photos</span>
                                            <input type="file" name="files[]" multiple>
                                        </span>
                        <button type="submit" class="btn btn-primary start">
                            <i class="icon-upload icon-white"></i>
                            <span>start upload</span>
                        </button>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="icon-ban-circle icon-white"></i>
                            <span>cancel</span>
                        </button>

                        <div class="divider"> |</div>

                        <button type="button" class="btn btn-primary btn-facebook" onclick="openImportFromFacebook('{$urlPrefix}fbpics/fbpics.php');">
                            <!--img src="{$urlPrefix}images/uploadPhotosFromFacebookButton.png" alt="Upload photos from Facebook" -->
                            <i class="icon-user icon-white"></i>
                            <span>from facebook</span>
                        </button>
                        <!-- button type="button" class="btn btn-primary" onclick="openImportFromFlickr('{$urlPrefix}phpFlickr/getphotos.php');">
                            <i class="icon-picture icon-white"></i>
                            <span>From Flickr</span>
                        </button-->
                    </div>

                    <!-- The global progress information -->
                    <div class="span5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="bar" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress information -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>

                </div>
                <!-- The loading indicator is shown during file processing -->
                <div class="fileupload-loading"></div>
                <br>
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped">
                    <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
                </table>
            </form>
        </div>
    </fieldset>
    <input type="hidden" id="userId" value="{$userId}">
</div> <!-- albums -->


    {else}
<div class="noResults"> You must be logged in</div>
{/if}
