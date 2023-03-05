<?php

use Plib\HtmlView as View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var array<string,array{href:string,src:string,alt:string}> $languages
 */
?>
<!-- polyglot languagemenu -->
<?foreach($languages as $language):?>
<a href="<?=$this->esc($language['href'])?>">
  <img src="<?=$this->esc($language['src'])?>" alt="<?=$this->esc($language['alt'])?>" title="<?=$this->esc($language['alt'])?>"/>
</a>
<?endforeach?>
