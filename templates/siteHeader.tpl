<div id="header">

    <div id="userBox" class="fr">
    {login assign="login"}
    {if $login.isLoggedIn == true}

        <div id="userBoxLinks">
            <div id="userBoxLinksInner">
                <span class="rightMenu"><a href="{$urlPrefix}scrapshare/">share <img src="{$urlPrefix}images/doubleRightArrowBlack.png"> </a></span> <br />
                <span class="rightMenu"><a href="{$urlPrefix}profile/">profile <img src="{$urlPrefix}images/doubleRightArrowBlack.png" id="goToProfile"> </a></span> <br />
                <span class="rightMenu"><a href="{$urlPrefix}{$login.logoutUrl}">log out <img src="{$urlPrefix}images/doubleRightArrowBlack.png"> </a></span><br />
            </div>
        </div>

        <div id="userBoxPicName">
            <div class="rightMenu"> {$login.tlname} </div>
            {if file_exists($login.profilepic)}
                <img src="{$urlPrefix}userpics/{$login.userid}/profile.png{$cacheBust}">
                {else}
                <img src="{$urlPrefix}img/noProfilePicture.jpg{$cacheBust}">
            {/if}
        </div>

        {else}

        <span id="loginButton">Click here to login or signup!</span>
        &nbsp;{include file="login/loginHolder.tpl"}

    {/if}
    </div>
</div>

<div id="topMenu">
    <a href="{$urlPrefix}"><img src="{$urlPrefix}images/homeButton{if $page == 'home'}Pressed{/if}.png" alt=""></a>
    <a href="{$urlPrefix}scrapbooks/"><img src="{$urlPrefix}images/scrapbooksButton{if $page == 'scrapbooks'}Pressed{/if}.png" alt=""></a>
    <a href="{$urlPrefix}scraplooks/"><img src="{$urlPrefix}images/scraplooksButton{if $page == 'scraplooks'}Pressed{/if}.png" alt=""></a>
    <a href="#" id="scraproomButton"><img src="{$urlPrefix}images/scraproomButton.png" alt=""></a>
    <a href="{$urlPrefix}album/"><img src="{$urlPrefix}images/photosButton{if $page == 'album'}Pressed{/if}.png" alt=""></a>
    <a href="{$urlPrefix}aboutus/"><img src="{$urlPrefix}images/aboutusButton{if $page == 'aboutus'}Pressed{/if}.png" alt=""></a>

</div>