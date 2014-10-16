<?php $this->preventAccess()?>
<!-- Polyglott_XH: page data tab -->
<form id="polyglott_pagedata" action="<?php echo $action;?>" method="post" onsubmit="return true">
    <div>
        <label for="polyglott_tag">Tag</label><br/>
        <input id="polyglott_tag" type="text" name="polyglott_tag" value="<?php echo $tag;?>"/>
    </div>
    <div style="text-align:right">
        <input type="submit" name="save_page_data" value="<?php echo $submit;?>"/>
    </div>
</form>
