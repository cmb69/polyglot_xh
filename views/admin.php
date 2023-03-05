<?php

use Plib\HtmlView as View;
use Plib\Url;

if (!isset($this)) {
    header("HTTP/1.1 404 Not found");
    exit;
}

/**
 * @var View $this
 * @var array<int,string> $languages
 * @var list<array{heading:string,url:Url,indent:string,tag:string,translations:array<string,?Url>}> $pages
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
        <th><?=$this->esc($language)?></th>
<?php endforeach?>
      </tr>
    </thead>
    <tbody>
<?php foreach ($pages as $page):?>
      <tr>
        <td>
          <a href="<?=$this->esc($page['url']->relative())?>" style="padding-left: <?=$this->esc($page['indent'])?>em"><?=$this->esc($page['heading'])?></a>
        </td>
        <td><?=$this->esc($page['tag'])?></td>
<?php     foreach ($page['translations'] as $translation):?>
        <td>
<?php         if (isset($translation)):?>
          <a href="<?=$this->esc($translation->relative())?>"><?=$this->text('label_ok')?></a>
<?php         endif?>
        </td>
<?php     endforeach?>
      </tr>
<?php endforeach?>
    </tbody>
  </table>
</div>
