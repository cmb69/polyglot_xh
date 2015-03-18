<?php

/**
 * Page data tab of Polyglott_XH.
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
 * Returns the page data tab view.
 *
 * @param array $pageData The page data of the current page.
 *
 * @return string The (X)HTML.
 */
function Polyglott_view(array $pageData)
{
    $command = new Polyglott_PageDataTabCommand($pageData);
    ob_start();
    $command->execute();
    return ob_get_clean();
}

?>
