<?php $this->preventAccess()?>
<!-- Polyglott_XH: language menu -->
<?php foreach($languages as $language):?>
<a href="<?php echo $language['href'];?>">
    <img src="<?php echo $language['src'];?>"
         alt="<?php echo $language['alt'];?>"
         title="<?php echo $language['alt'];?>"/>
</a>
<?php endforeach;?>
