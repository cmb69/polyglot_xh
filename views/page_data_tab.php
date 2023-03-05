<?php

use Plib\HtmlView as View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var string $action
 * @var string $tag
 */
?>
<!-- polyglot page data tab -->
<form id="polyglot_pagedata" action="<?=$this->esc($action)?>" method="post" onsubmit="return true">
  <p>
    <label>
      <div><?=$this->text('label_tag')?></div>
      <input type="text" name="polyglot_tag" value="<?=$this->esc($tag)?>"/>
    </label>
  </p>
  <div style="text-align:right">
    <input type="submit" name="save_page_data" value="<?=$this->text("label_save")?>"/>
  </div>
</form>
