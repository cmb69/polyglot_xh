<?php foreach($this->languages as $language):?>
<a href="<?=$language['href']?>">
    <img src="<?=$language['src']?>"
         alt="<?=$language['alt']?>"
         title="<?=$language['alt']?>"/>
</a>
<?php endforeach?>
