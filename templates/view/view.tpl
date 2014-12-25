<div id="profileHolder">
{viewProfile assign="profile"}
{if $profile.userFound == TRUE}

    <script type="text/javascript">

    </script>
    <div id="profileHeader" class="ib">
        <div class="big ib">{$profile.login.displayName|capitalize:true}'s Profile <span class="userClassType">-
            {if isset($profile.paidUser) && $profile.paidUser}
                <span class="paidUser">paid user</span>
                {else}
                <span class="regularUser">normal user</span>
            {/if}
                </span>
        </div>
    </div>
    <div id="profileBody">

        <form id="aboutMe">
            <fieldset>
                <legend>About Me</legend>
                <div class="c">
                    {if file_exists($profile.profilePic)}
                        <img src="{$urlPrefix}userpics/{$profile.id}/profile.png{$cacheBust}" class="profilePicture">
                        {else}
                        {$profile.profilePic}
                        <img src="{$urlPrefix}img/noProfilePicture.jpg{$cacheBust}" class="profilePicture" style="width:102px; height:151px; ">
                    {/if}
                </div>
                <div class="c">
                    <div class="c">
                        {$profile.login.description}
                    </div>
                </div>

            </fieldset>
        </form>

        <form id="scrapbooks">
            <fieldset>
                <legend>Scrapbooks</legend>
            {include file="../gallery/gallery.tpl"}
            </fieldset>
        </form>

    </div>

    {else}

    <span>There's no such user!</span>

{/if}
</div>