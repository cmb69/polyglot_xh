<form id="polyglot_pagedata" action="<?=$this->action()?>" method="post" onsubmit="return true">
    <div>
        <label for="polyglot_tag">Tag</label><br/>
        <input id="polyglot_tag" type="text" name="polyglot_tag" value="<?=$this->tag()?>"/>
    </div>
    <div style="text-align:right">
        <input type="submit" name="save_page_data" value="<?=$this->submit()?>"/>
    </div>
</form>
