<?php

/**
 * Page data tab of Polyglott_XH.
 *
 * @package    Polyglott
 * @copyright  Copyright (c) 2012-2013 Christoph M. Becker <http://3-magi.net/>
 * @license    http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version    $Id$
 * @link       http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */


/**
 * Returns the page data tab view.
 *
 * @global object  The polyglott controller.
 * @param  array $page  The page data of the current page.
 * @return string  The (X)HTML.
 */
function Polyglott_view($page)
{
    global $_Polyglott;

    return $_Polyglott->pageDataTab($page);
}

?>
