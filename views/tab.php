<form id="polyglott_pagedata" action="<?php echo $this->action();?>" method="post" onsubmit="return true">
    <div>
        <label for="polyglott_tag">Tag</label><br/>
        <input id="polyglott_tag" type="text" name="polyglott_tag" value="<?php echo $this->tag();?>"/>
    </div>
    <div style="text-align:right">
        <input type="submit" name="save_page_data" value="<?php echo $this->submit();?>"/>
    </div>
</form>
