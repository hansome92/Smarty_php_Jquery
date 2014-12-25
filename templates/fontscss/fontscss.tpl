{items cat_id=$smarty.const.FONTS_CAT_ID assign=fonts}
{foreach from=$fonts item=font}@font-face{ldelim}font-family: '{$font.name}';src: url('{$urlPrefix}images/{$font.font_css}');{rdelim}{/foreach}
