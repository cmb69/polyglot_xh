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
define('POLYGLOTT_VERSION', '1alpha1');


/**
 * Procedural wrapper for $_Polyglott->languageMenu().
 *
 * @global object  The polyglott controller.
 * @return string  The (X)HTML.
 */
function Polyglott_languageMenu()
{
    global $_Polyglott;

    return $_Polyglott->languageMenu();
}


/**
 * Procedural wrapper for $_Polyglott->alternateLinks().
 *
 * @global object  The polyglott controller.
 * @return string  The (X)HTML.
 */
function Polyglott_alternateLinks()
{
    global $_Polyglott;

    return $_Polyglott->alternateLinks();
}


/*
 * Instanciate the model.
 */
$_Polyglott = new Polyglott_Controller();

?>
