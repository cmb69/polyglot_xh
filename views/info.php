<?php

use Polyglot\View;

/**
 * @var View $this
 * @var stdClass[] $checks
 * @var string $version
 */
?>
<h1>Polyglot <?=$this->escape($version)?></h1>
<div class="polyglot_syscheck">
    <h2><?=$this->text('syscheck_title')?></h2>
<?php foreach ($checks as $check):?>
    <p class="xh_<?=$this->escape($check->state)?>"><?=$this->text('syscheck_message', $check->label, $check->stateLabel)?></p>
<?php endforeach?>
</div>
