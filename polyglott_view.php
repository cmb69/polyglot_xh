<?php

function polyglott_view($page) {
    global $sn, $su, $tx;

    $url = $sn.'?'.$su;
    $o = '<form id="polyglott_pagedata" action="'.$url.'" method="POST">'."\n"
	    .'<div>'."\n"
	    .'<label for="polyglott_tag">Tag</label>'.tag('br')
	    .tag('input id="polyglott_tag" type="text" name="polyglott_tag" value="'
		.$page['polyglott_tag'].'"')."\n"
	    .tag('input type="hidden" name="save_page_data"')."\n"
	    .'</div>'."\n"
	    .'<div style="text-align:right">'."\n"
	    .tag('input type="submit" value="'.ucfirst($tx['action']['save']).'"')."\n"
	    .'</div>'."\n"
	    .'</form>'."\n";
    return $o;
}

?>
