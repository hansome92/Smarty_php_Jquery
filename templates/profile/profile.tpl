<div id="profilePageHolder">
{login assign="login"}
{if $login.isLoggedIn == true}
    {myProfile assign="profile"}

    <div id="profileBody">
        <div style="clear:both;"><br></div>
        <img src="{$urlPrefix}images/profileEditProfileHeader.png" alt="profile">

        <!-- div id="profileHeader" class="ib">
            <div class="big ib">{$profile.login.displayName|capitalize:true}'s Profile <span class="userClassType">-

                </span>
            </div>
            <div class="small ib"> {$profile.login.name|capitalize:true} - {$profile.login.contactEmail} </div>
        </div -->

        {if $profile.paidUser}
            <span class="paidUser subscribeForYear">paid user</span>
            {else}
            {include file='paypal.tpl'}
        {/if}

        <form id="profilePicture" method="post" action="{$urlPrefix}saveProfile.php" enctype="multipart/form-data">
            <fieldset>
                <div class="profileItem">
                    <div class="item_view">
                        {if file_exists($profile.profilePic)}
                            <img src="{$urlPrefix}userpics/{$profile.id}/profile.png{$cacheBust}" class="profilePicture">
                            {else}
                            <img src="{$urlPrefix}img/noProfilePicture.jpg{$cacheBust}" class="profilePicture" style="width:102px; height:151px " ">
                        {/if}
                    </div>
                    <div class="item_file">

                        <div class="swf_upload_file">
                            <input type="file" id="profilePic" name="profilePic" class="fallback">
                            <span id="spanButtonPlaceholder"></span>

                            <div class="fieldset flash" id="fsUploadProgress"></div>
                            <br />
                        </div>
                        <img src="{$urlPrefix}images/profileSaveChangesButton.png" onclick="swfu.startUpload();" style="cursor: pointer;">
                    </div>
                    <div id="free_div"></div>
                </div>
                <input type="hidden" name="section" value="profilePicture">
            </fieldset>
        </form>

        <form id="accountInformation">
            <fieldset>
                <div class="profileItem">
                    <label for="displayName">TrinkeLily name:</label>
                    <input type="text" id="displayName" name="displayName" value="{$profile.login.displayName}">
                </div>
                <div class="profileItem">
                    <label for="fName">first name: <br> <span class="small">(private)</span></label>
                    <input type="text" id="fName" name="fName" value="{$profile.login.fname}">
                </div>
                <div class="profileItem">
                    <label for="lName">last name: <br> <span class="small">(private)</span></label>
                    <input type="text" id="lName" name="lName" value="{$profile.login.lname}">
                </div>
                <div class="profileItem">
                    <label for="email">contact email: <br> <span class="small">(private)</span></label>
                    <input type="text" id="email" name="email" size="30" value="{$profile.login.contactEmail}">
                </div>
                <div class="profileItem">
                    <label for="uDescription">about me: </label>
                    <textarea id="uDescription" name="uDescription" rows="4" cols="30">{$profile.login.description}</textarea>
                </div>
                <img src="{$urlPrefix}images/profileSaveChangesButton.png" id="updateAccountInformationButton" class="profileSaveButton">
            </fieldset>
        </form>

    </div>
    <script type="text/javascript">

    </script>

    <script type="text/javascript">
        var swfu;
        swfuSettings.post_params = {ldelim} "PHPSESSID": "{$profile.session_id}", "section": "profilePicture", 'flashUploader': 'true' {rdelim},
        window.onload = function() {
            swfu = new SWFUpload( swfuSettings );
        };
    </script>

    {else}

    <span>You're not signed in!</span>

{/if}
</div>