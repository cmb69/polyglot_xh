<?php

/**
 * The controller class of Polyglott_XH.
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
 * The controller class.
 *
 * @category CMSimple_XH
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class Polyglott_Controller
{
    /**
     * The model instance.
     *
     * @var object
     */
    protected $model;

    /**
     * Initialize a newly created instance.
     *
     * @global string The (X)HTML to insert into the head element.
     * @global string The current language.
     * @global array  The paths of system files and folders.
     * @global array  The URLs of the pages.
     * @global array  The configuration of the core.
     * @global object The page data router.
     */
    public function Polyglott_Controller()
    {
        global $hjs, $sl, $pth, $u, $cf, $pd_router;

        $dataFolder = $pth['folder']['plugins'] . 'polyglott/data/';
        $this->model = new Polyglott_Model(
            $sl, $cf['language']['default'], $pth['folder']['base'],
            $dataFolder
        );
        $contentLastMod = filemtime($pth['file']['content']);
        $pageDataLastMod = file_exists($pth['file']['pagedata'])
            ? filemtime($pth['file']['pagedata'])
            : 0;
        $tagsLastMod = $this->model->lastMod();
        $needsUpdate = $tagsLastMod < max($contentLastMod, $pageDataLastMod);
        if ($this->model->init($needsUpdate)) {
            if ($needsUpdate) {
                if (!$this->model->update($pd_router->find_all(), $u)) {
                    e('cntsave', 'file', $this->model->tagsFile());
                }
            }
        } else {
            e('cntopen', 'file', $this->model->tagsFile());
        }
        $pd_router->add_interest('polyglott_tag');
        if (XH_ADM) {
            $pd_router->add_tab(
                'Polyglott',
                $pth['folder']['plugins'] . 'polyglott/polyglott_view.php'
            );
            $this->dispatch();
        }
        $hjs .= $this->alternateLinks();
    }

    /**
     * Dispatches according to request.
     *
     * @return void
     *
     * @global string The value of the GET/POST parameter <var>admin</var>.
     * @global string The value of the GET/POST parameter <var>action</var>.
     * @global string The (X)HTML for the contents area.
     * @global string Whether Polyglott's administration is requested.
     */
    protected function dispatch()
    {
        global $admin, $action, $o, $polyglott;

        if (isset($polyglott) && $polyglott == 'true') {
            $o .= print_plugin_admin('on');
            switch ($admin) {
            case '':
                $o .= $this->info();
                break;
            case 'plugin_main':
                $o .= $this->administration();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'polyglott');
            }
        }
    }

    /**
     * Returns the alternate hreflang links.
     *
     * @return void
     *
     * @global int The index of the requested page.
     */
    protected function alternateLinks()
    {
        global $s;

        $res = '';
        $languages = $this->model->languages();
        foreach ($languages as $language) {
            $tag = $this->pageTag($s);
            $href = $this->model->languageURL($language, $tag);
            $res .= tag(
                'link rel="alternate" hreflang="' . $this->hsc($language)
                . '" href="' . $this->hsc($href) . '"'
            );
            $res .= PHP_EOL;
        }
        return $res;
    }

    /**
     * Returns a string with special HTML characters escaped.
     *
     * @param string $str A string.
     *
     * @return string
     *
     * @todo Use XH_hsc if available.
     */
    protected function hsc($str)
    {
        return htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
    }

    /**
     * Renders a template.
     *
     * @param string $_template The name of the template.
     * @param array  $_bag      Variables available in the template.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the core.
     */
    protected function render($_template, $_bag)
    {
        global $pth, $cf;

        $_template = $pth['folder']['plugins'] . 'polyglott/views/'
            . $_template . '.htm';
        $_xhtml = $cf['xhtml']['endtags'];
        unset($pth, $cf);
        extract($_bag);
        ob_start();
        include $_template;
        $o = ob_get_clean();
        if (!$_xhtml) {
            $o = str_replace('/>', '>', $o);
        }
        return $o;
    }

    /**
     * Returns the system checks.
     *
     * @return array
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the core.
     * @global array The localization of the plugins.
     */
    protected function systemChecks() // RELEASE-TODO
    {
        global $pth, $tx, $plugin_tx;

        $ptx = $plugin_tx['polyglott'];
        $phpVersion = '5.0.0';
        $checks = array();
        $checks[sprintf($ptx['syscheck_phpversion'], $phpVersion)]
            = version_compare(PHP_VERSION, $phpVersion) >= 0 ? 'ok' : 'fail';
        foreach (array('pcre') as $ext) {
            $checks[sprintf($ptx['syscheck_extension'], $ext)]
                = extension_loaded($ext) ? 'ok' : 'fail';
        }
        $checks[$ptx['syscheck_magic_quotes']]
            = !get_magic_quotes_runtime() ? 'ok' : 'fail';
        $checks[$ptx['syscheck_encoding']]
            = strtoupper($tx['meta']['codepage']) == 'UTF-8' ? 'ok' : 'warn';
        $folders = array();
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'polyglott/' . $folder;
        }
        foreach ($folders as $folder) {
            $checks[sprintf($ptx['syscheck_writable'], $folder)]
                = is_writable($folder) ? 'ok' : 'warn';
        }
        return $checks;
    }

    /**
     * Returns the plugin information view.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     */
    protected function info()
    {
        global $pth, $plugin_tx;

        $ptx = $plugin_tx['polyglott'];
        $labels = array(
            'syscheck' => $ptx['syscheck_title'],
            'about' => $ptx['about']
        );
        foreach (array('ok', 'warn', 'fail') as $state) {
            $images[$state] = $pth['folder']['plugins']
                . 'polyglott/images/' . $state . '.png';
        }
        $checks = $this->systemChecks();
        $icon = $pth['folder']['plugins'] . 'polyglott/polyglott.png';
        $version = POLYGLOTT_VERSION;
        $bag = compact(
            'labels', 'images', 'checks', 'icon', 'version'
        );
        return $this->render('info', $bag);
    }

    /**
     * Returns a dictionary from language codes to labels.
     *
     * @return array
     *
     * @global array The configuration of the plugins.
     */
    protected function languageLabels()
    {
        global $plugin_cf;

        $pcf = $plugin_cf['polyglott'];
        $languages = explode(';', $pcf['languages']);
        $res = array();
        foreach ($languages as $language) {
            list($key, $value) = explode('=', $language);
            $res[$key] = $value;
        }
        return $res;
    }

    /**
     * Returns the path of a language flag.
     *
     * @param string $language The language code.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     */
    protected function languageFlag($language)
    {
        global $pth;

        $res = $pth['folder']['flags'] . $language . '.gif"';
        return $res;
    }

    /**
     * Returns a polyglott tag.
     *
     * @param int $index The index of the page.
     *
     * @return string
     *
     * @global object The page data router.
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

    /**
     * Returns the URL to another language.
     *
     * @param string $language A language code.
     *
     * @return string
     *
     * @global int The index of the current page.
     */
    protected function languageURL($language)
    {
        global $s;

        $tag = $s > 0 ? $this->pageTag($s) : null;
        $res = $this->model->languageURL($language, $tag);
        return $res;
    }

    /**
     * Returns the page data tab view.
     *
     * @param array $page The page data of the current page.
     *
     * @return string (X)HTML.
     *
     * @global string The script name.
     * @global string The URL of the current page.
     * @global array  The localization of the core.
     */
    public function pageDataTab($page)
    {
        global $sn, $su, $tx;

        $action = $sn . '?' . $su;
        $tag = $page['polyglott_tag'];
        $submit = ucfirst($tx['action']['save']);
        $bag = compact('action', 'tag', 'submit');
        return $this->render('tab', $bag);
    }

    /**
     * Returns the main administration view.
     *
     * @return string (X)HTML.
     *
     * @global string The script name.
     * @global int    The number of pages.
     * @global array  The headings of the pages.
     * @global array  The levels of the pages.
     * @global array  The URLs of the pages.
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the core.
     * @global object The page data router.
     */
    protected function administration()
    {
        global $sn, $cl, $h, $l, $u, $pth, $cf, $pd_router;

        $languages = $this->model->otherLanguages();
        $pages = array();
        for ($i = 0; $i < $cl; $i++) {
            $heading = $h[$i];
            $url = $sn . '?' . $u[$i];
            $indent = $l[$i] - 1;
            $tag = $this->pageTag($i);
            $translations = array();
            foreach ($languages as $language) {
                $translations[$language]
                    = $this->model->isTranslated($tag, $language)
                    ? $this->model->languageURL($language, $tag)
                    : null;
            }
            $pages[] = compact('heading', 'url', 'indent', 'tag', 'translations');
        }
        $bag = compact('languages', 'pages');
        return $this->render('admin', $bag);
    }

    /**
     * Returns the language menu.
     *
     * @return string  (X)HTML.
     */
    public function languageMenu()
    {
        $labels = $this->languageLabels();
        $languages = array();
        foreach ($this->model->otherLanguages() as $language) {
            $href = $this->languageURL($language);
            $src = $this->languageFlag($language);
            $alt = isset($labels[$language]) ? $labels[$language] : $language;
            $languages[$language] = compact('href', 'src', 'alt');
        }
        return $this->render('languagemenu', compact('languages'));
    }
}

?>
