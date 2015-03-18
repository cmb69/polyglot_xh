<?php

/**
 * The page data tab commands.
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
 * The page data tab commands.
 *
 * @category CMSimple_XH
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class Polyglott_PageDataTabCommand
{
    /**
     * The page data.
     *
     * @var array
     */
    protected $pageData;

    /**
     * Initializes a new instance.
     *
     * @param array $pageData The page data of the current page.
     */
    public function __construct(array $pageData)
    {
        $this->pageData = $pageData;
    }

    /**
     * Executes the command.
     *
     * @return void
     *
     * @global string The script name.
     * @global string The URL of the current page.
     * @global array  The localization of the core.
     */
    public function execute()
    {
        global $sn, $su, $tx;

        $action = $sn . '?' . $su;
        $tag = $this->pageData['polyglott_tag'];
        $submit = ucfirst($tx['action']['save']);
        $bag = compact('action', 'tag', 'submit');
        echo Polyglott_View::make('tab', $bag)->render();
    }
}

?>
