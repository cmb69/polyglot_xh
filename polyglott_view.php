<?php

/**
 * Page data tab of Polyglott_XH.
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

/**
 * Returns the page data tab view.
 *
 * @param array $page The page data of the current page.
 *
 * @return string The (X)HTML.
 *
 * @global Polyglott_Controller The plugin controller.
 */
function Polyglott_view($page)
{
    global $_Polyglott_controller;

    return $_Polyglott_controller->pageDataTab($page);
}

?>
