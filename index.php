<?php

/**
 * Index of Polyglott_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Polyglott
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */

/*
 * Prevent direct access and usage from unsupported CMSimple_XH versions.
 */
if (!defined('CMSIMPLE_XH_VERSION')
    || strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') !== 0
    || version_compare(CMSIMPLE_XH_VERSION, 'CMSimple_XH 1.5.4', 'lt')
) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    die(<<<EOT
Polyglott_XH detected an unsupported CMSimple_XH version.
Uninstall Polyglott_XH or upgrade to a supported CMSimple_XH version!
EOT
    );
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
 * @global Polyglott_Controller The polyglott controller.
 */
function Polyglott_languageMenu()
{
    global $_Polyglott_controller;

    return $_Polyglott_controller->languageMenu();
}

/**
 * The plugin controller.
 *
 * @var Polyglott_Controller
 */
$_Polyglott_controller = new Polyglott_Controller();
$_Polyglott_controller->dispatch();

?>
