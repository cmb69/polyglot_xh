<?php

use Plib\HtmlView as View;
use Polyglot\Url;

if (!isset($this)) {
    header("HTTP/1.1 404 Not found");
    exit;
}

/**
 * @var View $this
 * @var array<string,array{href:Url,src:string,alt:string}> $languages
 */
?>
<?php foreach($languages as $language):?>
<a href="<?=$this->esc($language['href']->relative())?>">
  <img src="<?=$this->esc($language['src'])?>"
     alt="<?=$this->esc($language['alt'])?>"
     title="<?=$this->esc($language['alt'])?>"/>
</a>
<?php endforeach?>
