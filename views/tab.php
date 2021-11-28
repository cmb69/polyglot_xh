<?php

use Plib\HtmlView as View;

if (!isset($this)) {
    header("HTTP/1.1 404 Not found");
    exit;
}

/**
 * @var View $this
 * @var string $action
 * @var string $tag
 * @var string $submit
 */
?>
<form id="polyglot_pagedata" action="<?=$this->esc($action)?>" method="post" onsubmit="return true">
  <div>
    <label for="polyglot_tag"><?=$this->text('label_tag')?></label><br/>
    <input id="polyglot_tag" type="text" name="polyglot_tag" value="<?=$this->esc($tag)?>"/>
  </div>
  <div style="text-align:right">
    <input type="submit" name="save_page_data" value="<?=$this->esc($submit)?>"/>
  </div>
</form>
