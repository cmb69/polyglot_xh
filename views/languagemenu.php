<?php

use Polyglot\View;

/**
 * @var View $this
 * @var array<string,array> $languages
 */
?>
<?php foreach($languages as $language):?>
<a href="<?=$this->escape($language['href'])?>">
  <img src="<?=$this->escape($language['src'])?>"
     alt="<?=$this->escape($language['alt'])?>"
     title="<?=$this->escape($language['alt'])?>"/>
</a>
<?php endforeach?>
