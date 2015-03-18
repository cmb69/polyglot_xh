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
 * Autoloads a plugin class.
 *
 * @param string $class A class name.
 *
 * @return void
 */
function Polyglott_autoload($class)
{
    global $pth;

    $parts = explode('_', $class, 2);
    if ($parts[0] == 'Polyglott') {
        include_once $pth['folder']['plugins'] . 'polyglott/classes/'
            . $parts[1] . '.php';
    }
}

spl_autoload_register('Polyglott_autoload');

?>
