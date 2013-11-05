<?php

/**
 * Index of Polyglott_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Polyglott
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2013 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Polyglott_XH
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
require $pth['folder']['plugin_classes'] . 'Model.php';

/**
 * The controller class.
 */
require $pth['folder']['plugin_classes'] . 'Controller.php';

/**
 * The plugin version.
 */
define('POLYGLOTT_VERSION', '@POLYGLOTT_VERSION@');

/**
 * Procedural wrapper for $_Polyglott->languageMenu().
 *
 * @return string (X)HTML.
 *
 * @global object The polyglott controller.
 */
function Polyglott_languageMenu()
{
    global $_Polyglott;

    return $_Polyglott->languageMenu();
}

/**
 * Procedural wrapper for $_Polyglott->alternateLinks().
 *
 * @return string (X)HTML.
 *
 * @global object The polyglott controller.
 */
function Polyglott_alternateLinks()
{
    global $_Polyglott;

    return $_Polyglott->alternateLinks();
}

/*
 * Instanciate the controller.
 */
$_Polyglott = new Polyglott_Controller();

?>
