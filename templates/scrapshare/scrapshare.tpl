{literal}
<style>
    .gray {
        color: gray;
    }

    .bold {
        font-weight: bolder;
    }

    #scrapshare .deleteContactEmail, #scrapshare .checkbox {
        position: relative;
        float: left;
        cursor: pointer;
        text-align: left;
    }

    #scrapshare .deleteContactEmail {
        margin-left: -60px;
        height: 21px;
        width: 21px;
        clear: left;
        background: url("../images/delete.png") no-repeat;
        background-size: 100% 100%;
        margin-top: 17px;
    }

    #scrapshare .checkbox {
        left: -20px;
        height: 44px;
        width: 47px;
        background: url("../images/green_checkbox.png") no-repeat;
    }

    #scrapshare .checkbox input {
        display: none;
    }

    #scrapshare  .checkbox input.show {
        display: inline;
    }

    #scrapshare  label {
        padding-left: 10px;
        float: left;
        text-align: left;
    }

    #scrapshareHolder {
        text-align: left;
    }

    #scrapshare  .scrapbookCheckbox {
        width: 10px;
    }

    #scrapshare  .scrapshareBooksHolder, #scrapshare .scrapshareContactsHolder {
        display: inline-block;
        font-family: Georgia;
        font-style: italic;
        font-weight: bold;

    }

    #scrapshare  .scrapshareBook .book_title, #scrapshare .scrapshareContact .book_title {
        /*
        margin-top:0px;
        display:inline-block;
        height:44px;
        */
        font-size: 18px;
        width: 400px;
        float: left;
        clear: right;
        margin-top: 15px;
    }

    #scrapshare  .scrapshareContact .book_title {
        width: 550px;
    }

    #scrapshare fieldset.scrapshareBooksHolder, #scrapshare fieldset.scrapshareContactsHolder {
        border-top: 2px dotted gray;
        margin-top: 40px;
        margin-left: 40px;
        padding-left: 120px;
        width: 400px;
    }

    #scrapshare  fieldset.scrapshareBooksHolder legend, #scrapshare  fieldset.scrapshareContactsHolder legend {
        position: relative;
        top: -7px;
        padding: 0 20px;
        margin-left: -20px;
        width: auto;
        font-family: clean;
        font-family: Georgia;
        font-size: 2em;
        font-weight: normal;
        color: gray;
    }

    #scrapshareHolder .scrapshareBook, #scrapshareHolder .scrapshareContact {
        display: inline-block;
        margin-left: 20px;
        padding-bottom: 10px;
        width: 600px;
        height: 25px;
    }

    #scrapshareHolder .scrapshareContact {
        width: 700px;
    }

    #scrapshareContacts {
        margin-top: 40px;
    }

    #scrapshare .scrapshareContact .book_title, #scrapshareNewContact input {
        font-style: normal;
        font-weight: normal;
    }

    #scrapshareNewContact label {
        float: none;
        text-align: center;
        padding: 0;
        width: 220px;
    }

    #scrapshareNewContact #contactName, #scrapshareNewContact #contactEmail {
        width: 220px;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    #scrapshareNewContact {
        margin-left: -80px;
        margin-top: 20px;
        display: inline-block;
        width: 600px;
    }

    #scrapshareNewContact label {
        font-size: 12px;
    }

    #scrapshareNewContact input {
        -webkit-box-shadow: inset 1px 1px 5px 0px rgba(0, 0, 0, .4);
        box-shadow: inset 1px 1px 5px 0px rgba(0, 0, 0, .4);
    }

    #scrapshareNewContact span {
        margin-left: 10px;
        display: inline-block;
    }

    #addNewContactButton {
        position: relative;
        cursor: pointer;
        color: #7cb9a8;
    }

        /* scrapbook sharing confirmation popup */
    #confirmSharePopup {
        text-align: left;
        display: none;
        background-color: white;
        border-radius: 25px;
        padding: 65px;
        border: 3px solid #57a696;
        font-family: clean;
        font-family: Georgia;
        font-style: italic;
        width: 520px;
        margin: auto;
    }

    #confirmSharePopup #closeConfirmShare {
        margin-right: -55px;
        margin-top: -55px;
        border-radius: 25px;
        border: 2px solid gray;
        color: #57a696;
        height: 25px;
        width: 25px;
        text-align: center;
        display: inline-block;
        cursor: pointer;
        float: right;
    }

    #confirmSharePopup #closeConfirmShare .clickToClose {
        position: relative;
        top: 3px;
        font-weight: bolder;
    }

    #confirmSharePopup ul {
        margin-left: 50px;
    }

    #confirmSharePopup h3.book_title.shareWith {
        margin-top: 40px;
    }

    #scrapshareSubmitShare, #scrapshareConfirmShare {
        background: url("../images/scrapshare_ShareButton.png") no-repeat;
        width: 212px;
        height: 39px;
        margin: 40px 40px 0 0;
        cursor: pointer;
        display: inline-block;
        float: right;
    }

    #scrapshareConfirmShare {
        background: url("../images/scrapshare_ConfirmButton.png") no-repeat;
        margin: 0;
    }
</style>
<script type="text/javascript" src="../js/jquery.custom_radio_checkbox.js"></script>
<script type="text/javascript">
    var elmHeight = "44";
    var selectedScrapbooks = [], selectedContacts = [];

    $( document ).ready( function() {
        $( ".checkbox" ).dgStyle();

        $( '#addNewContactButton' ).click( function() {
            var name = $( '#contactName' ).val();
            var email = $( '#contactEmail' ).val();
            var newContactId = -1;
            $.ajax( {
                        type: 'POST',
                        url: url_prefix + 'scrapshare.php',
                        data: {'action': 'addContact', 'name': name, 'email': email},
                        success: function( data ) {
                            var resp = $.parseJSON( data );
                            if( resp.error == 0 ) {
                                newContactId = resp.contactid;
                                var newContactHtml = '<div class="scrapshareContact"><div class="deleteContactEmail" contactid="' + newContactId + '"></div><div class="checkbox newContactMarker"><input type="checkbox" class="contactCheckbox" name="contacts[]" value="' + newContactId + '"></div>';
                                newContactHtml = newContactHtml + '<span class="book_title"><span class="name">' + name + '</span> <span class="email">(' + email + ')</span></span></div>';
                                $( '#contacts' ).append( newContactHtml );
                                $( '.newContactMarker' ).removeClass( 'newContactMarker' ).dgStyle();
                            } else {
                                alert( resp.message );
                            }
                        }
                    } );
        } );

        $( '.deleteContactEmail' ).live( 'click', function() {
            var email = $( this ).parent().find( '.email' ).text(), name = $( this ).parent().find( '.name' ).text(), contactid = $( this ).attr( 'contactid' ), $that = $( this );
            if( confirm( "Are you sure you want to delete the contact for " + name + " " + email + "?" ) ) {
                $.ajax( {
                            type: 'POST',
                            url: url_prefix + 'scrapshare.php',
                            data: {'action': 'delete', 'contactid': contactid},
                            success: function( data ) {
                                alert( data );
                                $that.parent().remove();
                                //var resp = $.parseJSON( data );
                                //if( resp.error == 0 ) {} else {}
                            }
                        } );
            }
        } );

        $( '#scrapshareSubmitShare' ).click( function() {
            selectedScrapbooks = [];
            selectedContacts = [];
            $( '#scrapbooksToShare' ).html( '<ul></ul>' );
            $( '#contactsToShareWith' ).html( '<ul></ul>' );

            var $popupScrapbooks = $( '#scrapbooksToShare ul' );
            $( 'input:checkbox[name="scrapbooks[]"]:checked' ).each( function() {
                selectedScrapbooks.push( $( this ).val() );
                $popupScrapbooks.append( '<li>' + getScrapShareName( this ) + '</li>' );

            } );
            var $popupContacts = $( '#contactsToShareWith ul' );
            $( 'input:checkbox[name="contacts[]"]:checked' ).each( function() {
                selectedContacts.push( $( this ).val() );
                $popupContacts.append( '<li>' + getScrapShareName( this ) + '</li>' );
            } );
            if( selectedScrapbooks.length > 0 && selectedContacts.length > 0 ) {
                $( '#confirmSharePopup' ).bPopup();
            } else {
                alert( 'Please select both the scrapbooks you\'d like to share, and the contacts you\'d like to share them with. ' );
            }

        } );

        $( '#scrapshareConfirmShare' ).click( function() {
            $.ajax( {
                        type: 'POST',
                        url: url_prefix + 'scrapshare.php',
                        data: {'action': 'share', 'scrapbooks': selectedScrapbooks, 'contacts': selectedContacts},
                        success: function( data ) {
                            alert( data );
                            $( '#confirmSharePopup' ).bPopup().close();
                            //var resp = $.parseJSON( data );
                            //if( resp.error == 0 ) {} else {}
                        }
                    } );
        } );

        $( '#closeConfirmShare' ).click( function() {
            $( '#confirmSharePopup' ).bPopup().close();
        } );

        function getScrapShareName( $checkbox, includeHtml ) {
            return $( $checkbox ).parent().siblings( 'span.book_title' ).html();
        }

    } );

</script>
{/literal}

<div id="scrapshareHolder">
{login assign="login"}
{if $login.isLoggedIn == true}
    {myProfile assign="profile"}
    <div id="scrapshareSubmitShare"></div>
    <form id="scrapshare">
        <fieldset id="scrapshareBooks" class="scrapshareBooksHolder">
            <legend>Scrapbooks</legend>
            <div id="books" class="scrapshareBooksHolder">
                {if sizeof($profile.scrapbooks) > 0}
                    {foreach from=$profile.scrapbooks item=book}
                        <div class="scrapshareBook">
                            <div class="checkbox"><input type="checkbox" class="scrapbookCheckbox" name="scrapbooks[]" value="{$book.id}"></div>
                            <span class="book_title">{$book.name}</span>
                        </div>
                    {/foreach}
                {/if}
            </div>
        </fieldset>
        <br>
        <fieldset id="scrapshareContacts" class="scrapshareContactsHolder">
            <legend>Contacts</legend>
            <div id="contacts" class="scrapshareContactsHolder">
                {if sizeof($profile.contacts) > 0}
                    {foreach from=$profile.contacts item=contact}
                        <div class="scrapshareContact">
                            <div class="deleteContactEmail" contactid="{$contact.contactid}"></div>
                            <div class="checkbox newContactMarker">
                                <input type="checkbox" class="contactCheckbox" name="contacts[]" value="{$contact.contactid}">
                            </div>
                            <span class="book_title"><span class="name">{$contact.name}</span> <span class="email">({$contact.email})</span></span>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <div id="scrapshareNewContact">
                <span>
                    <input id="contactName" type="text" name="contactName">
                    <br>
                    <label for="contactName">name</label>
                </span>
                <span>
                    <input id="contactEmail" type="text" name="contactEmail">
                    <span id="addNewContactButton"> add >> </span>
                    <br><label for="contactEmail"> email </label>
                </span>
            </div>
        </fieldset>

        <div id="confirmSharePopup">
            <div id="closeConfirmShare">
                <div class="clickToClose">X</div>
            </div>
            <h3 class="book_title gray">You Are Sharing:</h3>

            <div id="scrapbooksToShare"></div>
            <br>

            <h3 class="book_title shareWith">With</h3>

            <h3 class="book_title gray">Contacts:</h3>

            <div id="contactsToShareWith"></div>
            <div id="scrapshareConfirmShare"></div>
        </div>

    </form>

    {else}

    <span>You're not signed in!</span>

{/if}
</div>