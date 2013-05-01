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
 * Returns (X)HTML plugin version information.
 *
 * @global array  The paths of system files and folders. *
 * @return string
 */
function Polyglott_version()
{
    global $pth;

    return '<h1><a href="http://3-magi.net/?CMSimple_XH/Polyglott_XH">Polyglott_XH</a></h1>'
	. tag('img style="float: left; margin: 0 16px 16px 0" src="' . $pth['folder']['plugins'] . 'polyglott/polyglott.png" alt="Plugin icon"')
	. '<p style="margin-top: 1em">Version: ' . POLYGLOTT_VERSION . '</p>'
	. '<p>Copyright &copy; 2012-2013 <a href="http://3-magi.net/">Christoph M. Becker</a></p>'
	. '<p style="text-align: justify">This program is free software: you can redistribute it and/or modify'
	. ' it under the terms of the GNU General Public License as published by'
	. ' the Free Software Foundation, either version 3 of the License, or'
	. ' (at your option) any later version.</p>'
	. '<p style="text-align: justify">This program is distributed in the hope that it will be useful,'
	. ' but WITHOUT ANY WARRANTY; without even the implied warranty of'
	. ' MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the'
	. ' GNU General Public License for more details.</p>'
	. '<p style="text-align: justify">You should have received a copy of the GNU General Public License'
	. ' along with this program.  If not, see'
	. ' <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>';
}


/**
 * Returns the requirements information view.
 *
 * @global array  The paths of system files and folders.
 * @global array  The localization of the core.
 * @global array  The localization of the plugins.
 * @return string  The (X)HTML.
 */
function Polyglott_systemCheck() // RELEASE-TODO
{
    global $pth, $tx, $plugin_tx;

    define('POLYGLOTT_PHP_VERSION', '4.0.7');
    $ptx = $plugin_tx['polyglott'];
    $imgdir = $pth['folder']['plugins'] . 'polyglott/images/';
    $ok = tag('img src="' . $imgdir . 'ok.png" alt="ok"');
    $warn = tag('img src="' . $imgdir . 'warn.png" alt="warning"');
    $fail = tag('img src="' . $imgdir . 'fail.png" alt="failure"');
    $o = '<h4>' . $ptx['syscheck_title'] . '</h4>'
	. (version_compare(PHP_VERSION, POLYGLOTT_PHP_VERSION) >= 0 ? $ok : $fail)
	. '&nbsp;&nbsp;' . sprintf($ptx['syscheck_phpversion'], POLYGLOTT_PHP_VERSION)
	. tag('br');
    foreach (array('pcre') as $ext) {
	$o .= (extension_loaded($ext) ? $ok : $fail)
	    . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_extension'], $ext) . tag('br');
    }
    $o .= (!get_magic_quotes_runtime() ? $ok : $fail)
	. '&nbsp;&nbsp;' . $ptx['syscheck_magic_quotes'] . tag('br') . tag('br');
    $o .= (strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $warn)
	. '&nbsp;&nbsp;' . $ptx['syscheck_encoding'] . tag('br') . tag('br');
    foreach (array('config/', 'css/', 'languages/') as $folder) {
	$folder = $pth['folder']['plugins'] . 'polyglott/' . $folder;
	$o .= (is_writable($folder) ? $ok : $warn)
	    . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_writable'], $folder) . tag('br');
    }
    return $o;
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
 * @return array
 */
function Polyglott_tags()
{
    $tags = array();
    $langs = Polyglott_otherLanguages();
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
 * @return string  The (X)HTML.
 */
function Polyglott_admin()
{
    global $sn, $cl, $h, $l, $u, $pth, $cf, $pd_router;

    $langs = Polyglott_otherLanguages();
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
	    $o .= Polyglott_version() . tag('hr') . Polyglott_systemCheck();
	    break;
	case 'plugin_main':
	    $o .= Polyglott_admin();
	    break;
	default:
	    $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
