<?php

use Polyglot\View;

/**
 * @var View $this
 * @var string $action
 * @var string $tag
 * @var string $submit
 */
?>
<form id="polyglot_pagedata" action="<?=$this->escape($action)?>" method="post" onsubmit="return true">
    <div>
        <label for="polyglot_tag">Tag</label><br/>
        <input id="polyglot_tag" type="text" name="polyglot_tag" value="<?=$this->escape($tag)?>"/>
    </div>
    <div style="text-align:right">
        <input type="submit" name="save_page_data" value="<?=$this->escape($submit)?>"/>
    </div>
</form>
