{items cat_id=$cat_id type=$type assign=items}

{* these are the one-off things which are required for individual sections, but not stored in the database *}

{if $type eq 'font'}
<div class="font_textBox">
    <textarea type="text" id="txtFont">Type your text here...</textarea>
</div>
<p></p>
<div id="fontColorSelector">
    <div style="background-color: #000000"></div>
</div>
<div id="fontColorSelectorLabel">
    <div>color</div>
</div>
<div id="text-align-container">
    <span id="text-align-container-inner">
        <span class="text-align-span" id="text-left"></span>
        <span class="text-align-span" id="text-center"></span>
        <span class="text-align-span" id="text-right"></span>
        <span class="text-align-span" id="text-justify"></span>
    </span>
</div>

    {elseif $type eq 'treatment'}
<div id="preset-removeEffects" class="imageFilter" style="z-index: 111; ">
    <img src="images/filterpics/normal.png" class="treatmentImage">

    <div class="treatmentName">Normal</div>
</div>

    {elseif $type eq 'wallpaper'}
<div id="backgroundColorSelector">
    <div style="background-color: #000000"></div>
</div>
<div id="backgroundColorSelectorLabel">
    <div>color</div>
</div>
<div id="wallpaperSeparator"></div>
{/if}

{* these are all of the items, iterated, which were stored in the db *}
{foreach from=$items item=item}
    {if $type eq 'font'}
    <div class="fonts" id="{$item.font_js}" style="font-family: '{$item.name}'; z-index: 0 !important; ">
        <textarea disabled="disabled" style="font-family: '{$item.name}';">TrinketLily</textarea>
    </div>
        {elseif $type eq 'border'}
    <img src="{$urlPrefix}images/{$item.itemImage}" alt="trinket"  width="180px" class="pictureBorderFrame" />

        {elseif $type eq 'treatment'}
    <div id="preset-{$item.treatment}" class="imageFilter" style="z-index: 111; ">
        <img src="images/filterpics/{$item.name|lower}.png" class="treatmentImage">

        <div class="treatmentName">{$item.name}</div>
    </div>

        {elseif $type eq 'wallpaper'}
    <div class="wallpaperHolder">
        <img src="{$urlPrefix}images/{$item.itemImage}" width="180px" class="wallpaperImage" alt="wallpaper"/>
    </div>

        {elseif $type eq 'photo'}
    <div class="albums" id='{$item.id}'>
        <img src="{$item.path}" width="180px" alt="photos" class="album-title" />
        <br />
        <span>{$item.name}</span>
    </div>

        {else}
    <div class="trinketContainer" style="background-image:url({$urlPrefix}images/{$item.itemImage});">
        <img src="{$urlPrefix}images/{$item.itemImage}" alt="trinket" class="trinket album-title" />
    </div>
    {/if}
{/foreach}