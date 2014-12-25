{scraplooks assign=scraplooks}

<div id="scraplooksSampleBooksHolder">
    <div class="scraplooksSampleBookHolder first">
        <div class="scraplooksSampleBookImage">
        </div>
        <div class="scraplooksSampleBookRight">
            <div class="scraplooksSampleBookTitle">
                Test Title
            </div>
            <img src="{$urlPrefix}/images/scraplooksViewBook.png" class="scraplooksViewBook">
            <img src="{$urlPrefix}/images/scraplooksCreateBook.png" class="scraplooksCreateBook">
        </div>
    </div>

    <div class="scraplooksSampleBookHolder">
        <div class="scraplooksSampleBookImage">
        </div>
        <div class="scraplooksSampleBookRight">
            <div class="scraplooksSampleBookTitle">
                Test Title
            </div>
            <img src="{$urlPrefix}/images/scraplooksViewBook.png" class="scraplooksViewBook">
            <img src="{$urlPrefix}/images/scraplooksCreateBook.png" class="scraplooksCreateBook">
        </div>
    </div>
</div>

{foreach from=$scraplooks item=scraplook}
{include file="scraplooks/scraplookItem.tpl"}
{/foreach}