<?php

/**
 * Back-end of Polyglott_XH.
 *
 * @package    Polyglott
 * @copyright  Copyright (c) 2012-2013 Christoph M. Becker <http://3-magi.net/>
 * @license    http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version    $Id$
 * @link       http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */


/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/**
 * Returns the tags of the given $lang.
 *
 * @global array  The paths of system files and folders.
 * @global array  The configuration of the core. *
 * @param string $lang
 * @return array
 */
function Polyglott_pageData($lang)
{
    global $pth, $cf;

    $fn = $lang == $cf['language']['default']
	? $pth['folder']['base'] . 'content/pagedata.php'
	: $pth['folder']['base'] . $lang . '/content/pagedata.php';
    include($fn);
    $tags = array();
    foreach ($page_data as $i => $p) {
	$tags[] = isset($p['polyglott_tag']) ? $p['polyglott_tag'] : '';
    }
    return $tags;
}


/**
 * Returns the list of the tags for all languages.
 *
 * @global object  The polyglott controller instance.
 * @return array
 */
function Polyglott_tags()
{
    global $_Polyglott;

    $tags = array();
    $langs = $_Polyglott->_model->otherLanguages();
    foreach ($langs as $lang) {
	$tags[$lang] = Polyglott_pageData($lang);
    }
    return $tags;
}


/**
 * Returns the main administration view.
 *
 * @global string  The script name.
 * @global int  The number of pages.
 * @global array  The headings of the pages.
 * @global array  The levels of the pages.
 * @global array  The "URLs" of the pages.
 * @global array  The paths of system files and folders.
 * @global array  The configuration of the core.
 * @global object  The page data router.
 * @global object  The polyglott controller instance.
 * @return string  The (X)HTML.
 */
function Polyglott_admin()
{
    global $sn, $cl, $h, $l, $u, $pth, $cf, $pd_router, $_Polyglott;

    $langs = $_Polyglott->_model->otherLanguages();
    $tags = Polyglott_tags();
    $o = '<table>'
	. '<thead><tr><td>Heading</td><td>Tag</td>';
    foreach ($langs as $lang) {
	$o .= '<td>' . $lang . '</td>';
    }
    $o .= '</tr></thead><tbody>';
    for ($i = 0; $i < $cl; $i++) {
	$pd = $pd_router->find_page($i);
	$o .= '<tr>'
	    . '<td>' . str_repeat('&nbsp;&nbsp;', $l[$i] - 1)
	    . '<a href="' . $sn . '?' . $u[$i] . '">' . $h[$i] . '</a></td>'
	    . '<td>' . $pd['polyglott_tag'] . '</td>';
	foreach ($langs as $lang) {
	    if (!empty($pd['polyglott_tag'])
		&& in_array($pd['polyglott_tag'], $tags[$lang]))
	    {
		$url = $pth['folder']['base']
		    . ($lang == $cf['language']['default'] ? '' : $lang)
		    . '?polyglott=' . $pd['polyglott_tag'];
		$cell = '<a href="' . $url . '">' . $lang . '</a>';
	    } else {
		$cell = '';
	    }
	    $o .= '<td>' . $cell . '</td>';
	}
	$o .= '</tr>';
    }
    $o .= '</tbody></table>';
    return $o;
}


/**
 * Register the page data tab.
 */
$pd_router->add_tab(
    'Polyglott',
    $pth['folder']['plugins'] . 'polyglott/polyglott_view.php'
);


/**
 * Handle the plugin administration.
 */
if (isset($polyglott) && $polyglott == 'true') {
    $o .= print_plugin_admin('on');
    switch ($admin) {
	case '':
	    $o .= $_Polyglott->_info();
	    break;
	case 'plugin_main':
	    $o .= Polyglott_admin();
	    break;
	default:
	    $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
