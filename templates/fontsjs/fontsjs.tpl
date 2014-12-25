{items cat_id=$smarty.const.FONTS_CAT_ID assign=fonts}
{foreach from=$fonts item=font}
    {fetch file="images/{$font.font_js}"}
{/foreach}
