/* SWFUPLOAD.JS */
{fetch file='js/swfupload.js'}
/* SWFUPLOADQUEUE.JS */
{fetch file='js/swfupload.queue.js'}
/* FILEPROGRESS.JS */
{fetch file='js/fileprogress.js'}
/* HANDLERS.JS */
{fetch file='js/handlers.js'}
var swfuSettings = {
// Backend Settings
upload_url: url_prefix + 'saveProfile.php',
file_post_name: 'profilePic',
// File Upload Settings
file_size_limit: "6 MB",
file_types: "*.jpg; *.png; *.gif; *.jpeg",
file_types_description: "Images",
file_upload_limit: 1,
file_queue_limit: 1,
swfupload_preload_handler: preLoad,
swfupload_load_failed_handler: loadFailed,
file_queued_handler: fileQueued,
file_queue_error_handler: fileQueueError,
file_dialog_complete_handler: fileDialogComplete,
upload_start_handler: uploadStart,
upload_progress_handler: uploadProgress,
upload_error_handler: uploadError,
upload_success_handler: uploadSuccess,
upload_complete_handler: uploadComplete,
debug_handler: doDebug,
// Button Settings
button_image_url: "",
button_placeholder_id: "spanButtonPlaceholder",
button_width: 180,
button_height: 18,
button_text: '<span class="button">Select a picture (6MB max)</span>',
button_text_style: '.button { font-family:Georgia;font-size:18pt;font-style:italic;text-decoration:underline; } ',
button_text_top_padding: 0,
button_text_left_padding: 20,
button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
button_cursor: SWFUpload.CURSOR.HAND,
button_action: SWFUpload.BUTTON_ACTION.SELECT_FILES,
// Flash Settings
flash_url: url_prefix + "editor/swfupload.swf",
flash9_url: url_prefix + "editor/swfupload_FP9.swf",
custom_settings: {
upload_target: "divFileProgressContainer",
progressTarget: "fsUploadProgress",
thumbnail_width: 200,
thumbnail_height: 300,
thumbnail_quality: 100
},
debug: false
}
$( document ).ready( function() {
makeImagesFit( $( '.profilePicture' ).parent() );
$( '.profileSaveButton' ).click( function( e ) {
var theForm = $( this ).parent( 'fieldset' ).parent( "form" ).get( 0 );
if(isNull(theForm)){
return true;
}
var theSection = theForm.id;

if( theSection == 'profilePicture' || theSection == 'scrapbooks' ) {
return true;
}

var theContent = $( theForm ).serialize();
$.ajax( {
type: 'POST',
url: '{$urlPrefix}saveProfile.php',
data: { 'section': theSection, 'content': theContent },
success: function( data ) {
alert( data );
},
failure: function( data ) {
alert( 'error: ' + data );
}
} );

} );
} );