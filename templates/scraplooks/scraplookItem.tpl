<div class="scraplooksDivider"></div>
<div id="{$scraplook.name}" class="scraplook">
    <div class="lookImage">
        <!--a href="{$scraplook.videoUrl}"-->
        <img src="{$urlPrefix}images/{$scraplook.boardImage}" alt="">
        <!--/a-->
    </div>
    <div class="lookRight">
        <div class="lookText">
        {$scraplook.description}
        </div>
    {include file="scraplooks/scraplookMenu.tpl"}
    </div>
</div>

