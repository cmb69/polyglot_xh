<?php

/**
 * Index of Polyglott_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Polyglott
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */

/**
 * The plugin version.
 */
define('POLYGLOTT_VERSION', '@POLYGLOTT_VERSION@');

/**
 * Returns the language menu.
 *
 * @return string (X)HTML.
 */
function Polyglott_languageMenu()
{
    $command = new Polyglott\LanguageMenuCommand();
    ob_start();
    $command->execute();
    return ob_get_clean();
}

$temp = new Polyglott\Controller();
$temp->dispatch();
