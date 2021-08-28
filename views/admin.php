<?php

use Polyglot\View;

/**
 * @var View $this
 * @var string[] $languages
 * @var array<string,mixed> $pages
 */
?>
<h1>Polyglot – <?=$this->text('label_translations')?></h1>
<div class="polyglot_translations">
  <table>
    <thead>
      <tr>
        <th><?=$this->text('label_page')?></th>
        <th><?=$this->text('label_tag')?></th>
<?php foreach ($languages as $language):?>
        <th><?=$this->escape($language)?></th>
<?php endforeach?>
      </tr>
    </thead>
    <tbody>
<?php foreach ($pages as $page):?>
      <tr>
        <td>
          <a href="<?=$this->escape($page['url'])?>" style="padding-left: <?=$this->escape($page['indent'])?>em"><?=$this->escape($page['heading'])?></a>
        </td>
        <td><?=$this->escape($page['tag'])?></td>
<?php     foreach ($page['translations'] as $translation):?>
        <td>
<?php         if (isset($translation)):?>
          <a href="<?=$this->escape($translation)?>"><?=$this->text('label_ok')?></a>
<?php         endif?>
        </td>
<?php     endforeach?>
      </tr>
<?php endforeach?>
    </tbody>
  </table>
</div>
