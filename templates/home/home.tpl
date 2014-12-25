<script type="text/javascript">
    $( document ).ready( function() {
    {if isset($login) && $login.isLoggedIn}
        $( '#homeMiddleFreeBook' ).click( function() {
            $( '#scraproomButton' ).click();
        } );
        $( '#homeMiddleJoinYear' ).click( function() {
            window.location = "{$smarty.const.WEBSITE_URL}payment.php";
        } );
        {else}
        $( '#homeMiddleFreeBook' ).add( '#homeMiddleJoinYear' ).click( function() {
            $( '#loginButton' ).click();
        } );
    {/if}
        $( '#homeTopViewBooks' ).click( function() {
            window.location = "{$smarty.const.WEBSITE_URL}scraplooks/";
        } );
        $( '#homeTopLearnMore' ).click( function() {
            window.location = "{$smarty.const.WEBSITE_URL}aboutus/";
        } );

    } );
</script>
{home assign=home}
<div id="homeTopHolder">
    <div id="homeTopVideo">
        <img src="{$urlPrefix}images/{$home.topimage.image}" alt="Make your own scrapbook!">
    </div>

    <div id="homeTopMainText">
        <img src="{$urlPrefix}images/{$home.topdropcap.image}" alt="{$home.topdropcap.content}" id="homeTopDropcap">
        <span id="homeTopMainTextInner">
        {$home.toptext.content}
        </span>
    </div>

    <div id="homeTopButtonHolder">
        <img src="{$urlPrefix}images/home_topViewScrapbook.png" alt="Learn More!" id="homeTopViewBooks">
        <img src="{$urlPrefix}images/home_topLearnMore.png" alt="Learn More!" id="homeTopLearnMore">
    </div>

</div>

<div id="homeMiddleHolder">
    <div class="homeMiddleItemHolder">
        <div class="homeMiddleNumberHolder">
            <img src="{$urlPrefix}images/home_middleNumberOne.png" alt="One" id="homeMiddleNumberOne">
        </div>
        <div class="homeMiddleText">
        {$home.body1.content}
        </div>
        <div class="homeMiddleButtonHolder">
            <img src="{$urlPrefix}images/home_middleFreeBook.png" alt="Join for a year!" id="homeMiddleFreeBook">
        </div>
    </div>
    <div class="homeMiddleItemHolder">
        <div class="homeMiddleNumberHolder">
            <img src="{$urlPrefix}images/home_middleNumberTwo.png" alt="Two" id="homeMiddleNumberTwo">
        </div>
        <div class="homeMiddleText">
        {$home.body2.content}
        </div>
        <div class="homeMiddleButtonHolder">
            <img src="{$urlPrefix}images/home_middleJoinYear.png" alt="Join for a year!" id="homeMiddleJoinYear">
        </div>
    </div>
</div>

