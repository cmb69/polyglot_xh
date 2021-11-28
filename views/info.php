<?php

use Plib\HtmlView as View;

if (!isset($this)) {
    header("HTTP/1.1 404 Not found");
    exit;
}

/**
 * @var View $this
 * @var array<int,stdClass> $checks
 * @var string $version
 */
?>
<h1>Polyglot <?=$this->esc($version)?></h1>
<div class="polyglot_syscheck">
  <h2><?=$this->text('syscheck_title')?></h2>
<?php foreach ($checks as $check):?>
  <p class="xh_<?=$this->esc($check->state)?>"><?=$this->text('syscheck_message', $check->label, $check->stateLabel)?></p>
<?php endforeach?>
</div>
