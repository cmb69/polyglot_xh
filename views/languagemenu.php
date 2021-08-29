<?php

use Polyglot\View;

if (!isset($this)) {
    header("HTTP/1.1 404 Not found");
    exit;
}

/**
 * @var View $this
 * @var array<string,array{href:string,src:string,alt:string}> $languages
 */
?>
<?php foreach($languages as $language):?>
<a href="<?=$this->escape($language['href'])?>">
  <img src="<?=$this->escape($language['src'])?>"
     alt="<?=$this->escape($language['alt'])?>"
     title="<?=$this->escape($language['alt'])?>"/>
</a>
<?php endforeach?>
