<?php

/**
 * The views.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Polyglott
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */

/**
 * The views.
 *
 * @category CMSimple_XH
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class Polyglott_View
{
    /**
     * The path of the template file.
     *
     * @var string
     */
    protected $template;

    /**
     * The data.
     *
     * @var array<string, *>
     */
    protected $data;

    /**
     * Makes a new view object.
     *
     * @param string $template A template name.
     * @param array  $data     An array of data.
     *
     * @return Feedview_View
     */
    static public function make($template, $data)
    {
        return new self($template, $data);
    }

    /**
     * Initializes a new instance.
     *
     * @param string $template A template name.
     * @param array  $data     An array of data.
     *
     * @global array The paths of system files and folders.
     */
    protected function __construct($template, $data)
    {
        global $pth, $cf;

        $this->template = $pth['folder']['plugins'] . 'polyglott/views/'
            . $template . '.php';
        $this->data = $data;
    }

    /**
     * Renders the template.
     *
     * @return string (X)HTML.
     *
     * @global array The configuration of the core.
     */
    public function render()
    {
        global $cf;

        $html = $this->doRender();
        if (!$cf['xhtml']['endtags']) {
            $html = str_replace(' />', '>', $html);
        }
        return $html;
    }

    /**
     * Renders the template.
     *
     * @return string XHTML.
     */
    protected function doRender()
    {
        extract($this->data);
        ob_start();
        include $this->template;
        return ob_get_clean();
    }

    /**
     * Dummy to prevent direct access of template files.
     *
     * @return void
     */
    protected function preventAccess()
    {
        // pass
    }
}

?>
