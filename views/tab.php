<form id="polyglott_pagedata" action="<?=$this->action()?>" method="post" onsubmit="return true">
    <div>
        <label for="polyglott_tag">Tag</label><br/>
        <input id="polyglott_tag" type="text" name="polyglott_tag" value="<?=$this->tag()?>"/>
    </div>
    <div style="text-align:right">
        <input type="submit" name="save_page_data" value="<?=$this->submit()?>"/>
    </div>
</form>
