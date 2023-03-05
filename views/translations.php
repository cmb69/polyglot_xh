<?php

use Plib\HtmlView as View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var array<int,string> $languages
 * @var list<array{heading:string,url:string,indent:string,tag:string,translations:array<string,?string>}> $pages
 */
?>
<!-- polyglot translations -->
<h1>Polyglot – <?=$this->text('label_translations')?></h1>
<div class="polyglot_translations">
  <table>
    <thead>
      <tr>
        <th><?=$this->text('label_page')?></th>
        <th><?=$this->text('label_tag')?></th>
<?foreach ($languages as $language):?>
        <th><?=$this->esc($language)?></th>
<?endforeach?>
      </tr>
    </thead>
    <tbody>
<?foreach ($pages as $page):?>
      <tr>
        <td>
          <a href="<?=$this->esc($page['url'])?>" style="padding-left: <?=$this->esc($page['indent'])?>em"><?=$this->esc($page['heading'])?></a>
        </td>
        <td><?=$this->esc($page['tag'])?></td>
<?  foreach ($page['translations'] as $translation):?>
        <td>
<?    if (isset($translation)):?>
          <a href="<?=$this->esc($translation)?>"><?=$this->text('label_ok')?></a>
<?    endif?>
        </td>
<?  endforeach?>
      </tr>
<?endforeach?>
    </tbody>
  </table>
</div>
