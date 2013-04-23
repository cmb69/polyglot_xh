<?php

/**
 * Front-End of Polyglott_XH
 *
 * Copyright (c) 2012 Christoph M. Becker (see README.txt)
 */


define('POLYGLOTT_VERSION', '1dev2');


/**
 * Returns all available languages other than the current one.
 *
 * @return array
 */
function polyglott_other_languages() {
    global $sl, $cf, $pth;

    $langs = array($cf['language']['default']);
    $dh = opendir($pth['folder']['base']);
    while (($dir = readdir($dh)) !== FALSE) {
	if (preg_match('/^[A-z]{2}$/', $dir)) {
	    $langs[] = $dir;
	}
    }
    unset($langs[array_search($sl, $langs)]);
    return $langs;
}


/**
 * Redirect to the translated page, if it's been already translated.
 * Otherwise output appropriate message.
 *
 * @return void
 */
function polyglott_select_page($tag) {
    global $sn, $o, $u, $pd_router, $plugin_tx;

    $s = -1;
    if (!empty($tag)) {
	$pd = $pd_router->find_all();
	foreach ($pd as $i => $d) {
	    if (isset($d['polyglott_tag']) && $d['polyglott_tag'] == $tag) {
		$s = $i; break;
	    }
	}
    }
    //if ($s >= 0) {
	$url = 'http'.(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 's' : '').'://'
		.$_SERVER['SERVER_NAME'].$sn.($s >= 0 ? '?'.$u[$s] : '');
	//header('HTTP/1.1 301 Moved Permanently'); // TODO: as config option? at least: document! DO NOT 301!
	header('Location: '.$url);
	exit;
//    } else {
//	$o .=  $plugin_tx['polyglott']['not_translated'];
    //}
}


function Polyglott_languageLabels()
{
    global $plugin_cf;

    $pcf = $plugin_cf['polyglott'];
    $langs = explode(';', $pcf['languages']);
    $res = array();
    foreach ($langs as $lang) {
	list($key, $value) = explode('=', $lang);
	$res[$key] = $value;
    }
    return $res;
}


/**
 * Returns the language menu.
 *
 * @access public
 * @return string  The (X)HTML.
 */
function polyglott_languagemenu() {
    global $s, $cf, $pth, $pd_current;

    if ($s >= 0) {
	$tag = isset($pd_current['polyglott_tag']) ? $pd_current['polyglott_tag'] : FALSE;
	$polyglott = $tag ? '?polyglott='.$tag : ''; // TODO: ?polyglott=
    } else {
	$polyglott = '';//(!empty($_SERVER['QUERY_STRING']) ? '?' : '').$_SERVER['QUERY_STRING'];
    }
    $languages = Polyglott_languageLabels();
    $o = '';
    foreach (polyglott_other_languages() as $lang) {
	$url = $pth['folder']['base'].($lang == $cf['language']['default'] ? '' : $lang.'/').$polyglott;
	$alt = isset($languages[$lang]) ? $languages[$lang] : $lang;
	$o .= '<a href="'.$url.'">'
		.tag('img src="'.$pth['folder']['flags'].$lang.'.gif" alt="'.$alt.'"'
		    .' title="'.$alt.'"').'</a>';
    }
    return $o;
}


/**
 * Register page data field.
 */
$pd_router->add_interest('polyglott_tag');


/**
 * Handle switching to another language.
 */
if (isset($_GET['polyglott']) && $polyglott != 'true') {
    polyglott_select_page(stsl($_GET['polyglott']));
}

?>
