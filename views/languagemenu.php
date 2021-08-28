<?php

use Polyglot\View;

/**
 * @var View $this
 * @var array<string,array> $languages
 */
?>
<?php foreach($languages as $language):?>
<a href="<?=$language['href']?>">
  <img src="<?=$language['src']?>"
     alt="<?=$language['alt']?>"
     title="<?=$language['alt']?>"/>
</a>
<?php endforeach?>
