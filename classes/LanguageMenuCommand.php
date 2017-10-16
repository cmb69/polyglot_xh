<?php

/**
 * The language menu commands.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Polyglott
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2017 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */

namespace Polyglott;

/**
 * The language menu commands.
 *
 * @category CMSimple_XH
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class LanguageMenuCommand
{
    /**
     * The model instance.
     *
     * @var object
     */
    protected $model;

    /**
     * Initializes a new instance.
     */
    public function __construct()
    {
        global $pth, $sl, $cf;

        $this->model = new Model(
            $sl,
            $cf['language']['default'],
            $pth['folder']['base'],
            $pth['folder']['plugins'] . 'polyglott/cache/'
        );
        $this->model->init(false);
    }

    /**
     * Executes the command.
     *
     * @return void
     */
    public function execute()
    {
        $languages = array();

        foreach ($this->model->otherLanguages() as $language) {
            $href = XH_hsc($this->languageURL($language));
            $src = $this->languageFlag($language);
            $alt = XH_hsc($this->getAltAttribute($language));
            $languages[$language] = compact('href', 'src', 'alt');
        }
        echo View::make('languagemenu', compact('languages'))->render();
    }

    /**
     * Returns the path of a language flag.
     *
     * @param string $language The language code.
     *
     * @return string
     */
    protected function languageFlag($language)
    {
        global $pth, $plugin_cf;

        return $pth['folder']['flags'] . $language . '.'
            . $plugin_cf['polyglott']['flags_extension'];
    }

    /**
     * Returns the alt attribute for a language flag.
     *
     * @param string $language A language code.
     *
     * @return string
     */
    protected function getAltAttribute($language)
    {
        global $s;

        $tag = $this->pageTag($s);
        $labels = $this->languageLabels();
        if (isset($labels[$language])) {
            if ($this->model->isTranslated($tag, $language)
                || !isset($labels[$language][1])
            ) {
                $alt = $labels[$language][0];
            } else {
                $alt = $labels[$language][1];
            }
        } else {
            $alt = $language;
        }
        return $alt;
    }

    /**
     * Returns a dictionary from language codes to labels.
     *
     * @return array
     */
    protected function languageLabels()
    {
        global $plugin_cf;

        $pcf = $plugin_cf['polyglott'];
        $languages = preg_split('/\r\n|\r|\n/', $pcf['languages_labels']);
        $res = array();
        foreach ($languages as $language) {
            list($key, $value) = explode('=', $language);
            $res[$key] = explode(';', $value);
        }
        return $res;
    }

    /**
     * Returns the URL to another language.
     *
     * @param string $language A language code.
     *
     * @return string
     */
    protected function languageURL($language)
    {
        global $s;

        $tag = $s > 0 ? $this->pageTag($s) : null;
        $res = $this->model->languageURL($language, $tag);
        return $res;
    }

    /**
     * Returns a polyglott tag.
     *
     * @param int $index The index of the page.
     *
     * @return string
     */
    protected function pageTag($index)
    {
        global $pd_router;

        $pageData = $pd_router->find_page($index);
        $res = isset($pageData['polyglott_tag'])
            ? $pageData['polyglott_tag']
            : null;
        return $res;
    }
}
