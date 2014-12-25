<!DOCTYPE HTML>
<html>
    <head>
    {include file="globalHead.tpl"}
    {literal}

    {/literal}
    </head>
    <body>

        <div id="siteHolder">
            <div id="siteInnerBg">
                <div id="siteInnerBg2">
                    <div id="siteInnerBg3">
                        <!--div id="leftBorder"></div-->

                        <div id="siteInner">

                            <div id="headerHolder">
                            {include file="siteHeader.tpl"}
                            </div>

                            <div id="content">
                            {include file="$page/$page.tpl"}
                            </div>

                            <div id="siteFooter" class="looktext">
                            {include file="siteFooter.tpl"}
                            </div>

                        </div>

                        <!--div id="rightBorder"></div-->

                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
