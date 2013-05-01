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
 * @global string  The script name.
 * @global string  The "URL" of the current page.
 * @global array  The localization of the core.
 * @param  array $page  The page data of the current page.
 */
function Polyglott_view($page)
{
    global $sn, $su, $tx;

    $url = $sn . '?' . $su;
    $o = '<form id="polyglott_pagedata" action="' . $url . '" method="post">'
	. '<div>'
	. '<label for="polyglott_tag">Tag</label>' . tag('br')
	. tag('input id="polyglott_tag" type="text" name="polyglott_tag"'
	      . ' value="' . $page['polyglott_tag'] . '"')
	. tag('input type="hidden" name="save_page_data"')
	. '</div>'
	. '<div style="text-align:right">'
	. tag('input type="submit" value="' . ucfirst($tx['action']['save']) . '"')
	. '</div>'
	. '</form>';
    return $o;
}

?>
