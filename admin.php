<?php

/**
 * Back-End of Polyglott_XH
 *
 * Copyright (c) 2012 Christoph M. Becker (see README.txt)
 */


/**
 * Returns (X)HTML plugin version information.
 *
 * @return string
 */
function polyglott_version() {
    global $pth;

    return '<h1><a href="http://3-magi.net/?CMSimple_XH/Polyglott_XH">Polyglott_XH</a></h1>'."\n"
	    .tag('img class="polyglott_plugin_icon" src="'.$pth['folder']['plugins'].'polyglott/polyglott.png" alt="Plugin icon"')."\n"
	    .'<p style="margin-top: 1em">Version: '.POLYGLOTT_VERSION.'</p>'."\n"
	    .'<p>Copyright &copy; 2012 <a href="http://3-magi.net/">Christoph M. Becker</a></p>'."\n"
	    .'<p class="polyglott_license">This program is free software: you can redistribute it and/or modify'
	    .' it under the terms of the GNU General Public License as published by'
	    .' the Free Software Foundation, either version 3 of the License, or'
	    .' (at your option) any later version.</p>'."\n"
	    .'<p class="polyglott_license">This program is distributed in the hope that it will be useful,'
	    .' but WITHOUT ANY WARRANTY; without even the implied warranty of'
	    .' MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the'
	    .' GNU General Public License for more details.</p>'."\n"
	    .'<p class="polyglott_license">You should have received a copy of the GNU General Public License'
	    .' along with this program.  If not, see'
	    .' <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>'."\n";
}


/**
 * Returns the requirements information view.
 *
 * @return string  The (X)HTML.
 */
function polyglott_system_check() { // RELEASE-TODO
    global $pth, $tx, $plugin_tx;

    define('POLYGLOTT_PHP_VERSION', '4.0.7');
    $ptx = $plugin_tx['polyglott'];
    $imgdir = $pth['folder']['plugins'].'polyglott/images/';
    $ok = tag('img src="'.$imgdir.'ok.png" alt="ok"');
    $warn = tag('img src="'.$imgdir.'warn.png" alt="warning"');
    $fail = tag('img src="'.$imgdir.'fail.png" alt="failure"');
    $o = '<h4>'.$ptx['syscheck_title'].'</h4>'
	    .(version_compare(PHP_VERSION, POLYGLOTT_PHP_VERSION) >= 0 ? $ok : $fail)
	    .'&nbsp;&nbsp;'.sprintf($ptx['syscheck_phpversion'], POLYGLOTT_PHP_VERSION)
	    .tag('br')."\n";
    foreach (array() as $ext) {
	$o .= (extension_loaded($ext) ? $ok : $fail)
		.'&nbsp;&nbsp;'.sprintf($ptx['syscheck_extension'], $ext).tag('br')."\n";
    }
    $o .= (!get_magic_quotes_runtime() ? $ok : $fail)
	    .'&nbsp;&nbsp;'.$ptx['syscheck_magic_quotes'].tag('br').tag('br')."\n";
    $o .= (strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $warn)
	    .'&nbsp;&nbsp;'.$ptx['syscheck_encoding'].tag('br').tag('br')."\n";
    foreach (array('config/', 'css/', 'languages/') as $folder) {
	$folder = $pth['folder']['plugins'].'polyglott/'.$folder;
	$o .= (is_writable($folder) ? $ok : $warn)
		.'&nbsp;&nbsp;'.sprintf($ptx['syscheck_writable'], $folder).tag('br')."\n";
    }
    return $o;
}


/**
 * Returns the tags of the given $lang.
 *
 * @param string $lang
 * @return array
 */
function polyglott_pagedata($lang) {
    global $cf, $pth;

    $fn = $lang == $cf['language']['default'] ? $pth['folder']['base'].'content/pagedata.php'
	    : $pth['folder']['base'].$lang.'/content/pagedata.php';
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
function polyglott_tags() {
    $tags = array();
    $langs = polyglott_other_languages();
    foreach ($langs as $lang) {
	$tags[$lang] = polyglott_pagedata($lang);
    }
    return $tags;
}


/**
 * Returns the main administration view.
 *
 * @return string  The (X)HTML.
 */
function polyglott_admin() {
    global $sn, $cl, $h, $l, $u, $pth, $cf, $pd_router;

    $langs = polyglott_other_languages();
    $tags = polyglott_tags();
    $o = '<table>'
	    .'<thead><tr><td>Heading</td><td>Tag</td>';
    foreach ($langs as $lang) {
	$o .= '<td>'.$lang.'</td>';
    }
    $o .= '</tr></thead><tbody>';
    for ($i = 0; $i < $cl; $i++) {
	$pd = $pd_router->find_page($i);
	$o .= '<tr>'
		.'<td>'.str_repeat('&nbsp;&nbsp;', $l[$i] - 1).'<a href="'.$sn.'?'.$u[$i].'">'.$h[$i].'</a></td>'
		.'<td>'.$pd['polyglott_tag'].'</td>';
	foreach ($langs as $lang) {
	    if (!empty($pd['polyglott_tag']) && in_array($pd['polyglott_tag'], $tags[$lang])) {
		$url = $pth['folder']['base'].($lang == $cf['language']['default'] ? '' : $lang).'?polyglott='.$pd['polyglott_tag'];
		$cell = '<a href="'.$url.'">'.$lang.'</a>';
	    } else {
		$cell = '';
	    }
	    $o .= '<td>'.$cell.'</td>';
	}
	$o .= '</tr>';
    }
    $o .= '</tbody></table>';
    return $o;
}


/**
 * Register the page data tab.
 */
$pd_router->add_tab('Polyglott', $pth['folder']['plugins'].'polyglott/polyglott_view.php');


/**
 * Handle the plugin administration.
 */
if (isset($polyglott) && $polyglott == 'true') {
    $o .= print_plugin_admin('on');
    switch ($admin) {
	case '':
	    $o .= polyglott_version().tag('hr').polyglott_system_check();
	    break;
	case 'plugin_main':
	    $o .= polyglott_admin();
	    break;
	default:
	    $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
