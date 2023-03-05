<?php

use Plib\HtmlView as View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var array<array{state:string,label:string,state_label:string}> $checks
 * @var string $version
 */
?>
<!-- polyglot plugin info -->
<h1>Polyglot <?=$this->esc($version)?></h1>
<div class="polyglot_syscheck">
  <h2><?=$this->text('syscheck_title')?></h2>
<?foreach ($checks as $check):?>
  <p class="xh_<?=$this->esc($check["state"])?>"><?=$this->text('syscheck_message', $check["label"], $check["state_label"])?></p>
<?endforeach?>
</div>
