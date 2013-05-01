<?php

/**
 * Index of Polyglott_XH.
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
 * The model class.
 */
require $pth['folder']['plugin_classes'] . 'model.php';
require $pth['folder']['plugin_classes'] . 'controller.php';


/**
 * The plugin version.
 */
define('POLYGLOTT_VERSION', '1dev2');


/**
 * Redirect to the translated page.
 * If page is not translated, redirect to start page.
 *
 * @global string  The script name.
 * @global array  The "URLs" of the pages.
 * @global array  The localization of the plugins.
 * @global object  The page data router.
 */
function Polyglott_selectPage($tag)
{
    global $sn, $u, $plugin_tx, $pd_router;

    $s = -1;
    if (!empty($tag)) {
	$pd = $pd_router->find_all();
	foreach ($pd as $i => $d) {
	    if (isset($d['polyglott_tag']) && $d['polyglott_tag'] == $tag) {
		$s = $i;
		break;
	    }
	}
    }
    $url = 'http'
	. (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 's' : '')
	. '://' . $_SERVER['SERVER_NAME'] . $sn . ($s >= 0 ? '?' . $u[$s] : '');
    header('Location: '.$url);
    exit;
}


/**
 * Returns the language menu.
 *
 * @access public
 *
 * @global int  The index of the current page.
 * @global array  The paths of system files and folders.
 * @global array  The configuration of the core.
 * @global array  The page data of the current page.
 * @global object  The polyglott controller.
 * @return string  The (X)HTML.
 */
function Polyglott_languageMenu()
{
    global $s, $pth, $cf, $pd_current, $_Polyglott;

    if ($s >= 0) {
	$tag = isset($pd_current['polyglott_tag'])
	    ? $pd_current['polyglott_tag']
	    : false;
	$polyglott = $tag ? '?polyglott=' . $tag : '';
    } else {
	$polyglott = '';
    }
    $languages = $_Polyglott->_languageLabels();
    $o = '';
    foreach ($_Polyglott->_model->otherLanguages() as $lang) {
	$url = $pth['folder']['base']
	    . ($lang == $cf['language']['default'] ? '' : $lang . '/')
	    . $polyglott;
	$alt = isset($languages[$lang]) ? $languages[$lang] : $lang;
	$o .= '<a href="' . $url . '">'
	    . tag('img src="' . $pth['folder']['flags'] . $lang . '.gif"'
		  . ' alt="' . $alt . '" title="' . $alt . '"')
	    . '</a>';
    }
    return $o;
}


/*
 * Instanciate the model.
 */
$_Polyglott = new Polyglott_Controller();


/**
 * Register the page data field.
 */
$pd_router->add_interest('polyglott_tag');


/**
 * Handle switching to another language.
 */
if (isset($_GET['polyglott']) && $polyglott != 'true') {
    Polyglott_selectPage(stsl($_GET['polyglott']));
}

?>
