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
     * Initializes a new instance.
     *
     * @global array  The paths of system files and folders.
     * @global string The current language.
     * @global array  The configuration of the core.
     */
    public function __construct()
    {
        global $pth, $sl, $cf;

        $this->model = new Polyglott_Model(
            $sl, $cf['language']['default'], $pth['folder']['base'],
            $pth['folder']['plugins'] . 'polyglott/cache/'
        );
    }

    /**
     * Dispatches according to request.
     *
     * @return void
     *
     * @global string The (X)HTML to insert into the head element.
     * @global array  The paths of system files and folders.
     * @global object The page data router.
     */
    public function dispatch()
    {
        global $hjs, $pth, $pd_router;

        $this->updateCache();
        $pd_router->add_interest('polyglott_tag');
        if (XH_ADM) {
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(true);
            }
            $pd_router->add_tab(
                'Polyglott',
                $pth['folder']['plugins'] . 'polyglott/polyglott_view.php'
            );
            if ($this->wantsPluginAdministration()) {
                $this->handleAdministration();
            }
        }
        $hjs .= $this->alternateLinks();
    }

    /**
     * Updates the cache.
     *
     * @return void
     *
     * @global array  The paths of system files and folders.
     * @global array  The URLs of the pages.
     * @global object The page data router.
     */
    protected function updateCache()
    {
        global $pth, $u, $pd_router;

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
    }

    /**
     * Returns whether the plugin administration is requested.
     *
     * @return bool
     *
     * @global string Whether the plugin administration is requested.
     *
     * @todo Remove fallback for XH < 1.6.3.
     */
    protected function wantsPluginAdministration()
    {
        global $polyglott;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('polyglott')
            || isset($polyglott) && $polyglott == 'true';
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the GET/POST parameter <var>admin</var>.
     * @global string The value of the GET/POST parameter <var>action</var>.
     * @global string The (X)HTML for the contents area.
     */
    protected function handleAdministration()
    {
        global $admin, $action, $o;

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
        $tag = $this->pageTag($s);
        $languages = $this->model->languages();
        foreach ($languages as $language) {
            if ($this->model->hasTag($tag, $language)) {
                $href = $this->model->languageURL($language, $tag);
                $res .= tag(
                    'link rel="alternate" hreflang="' . $this->hsc($language)
                    . '" href="' . $this->hsc($href) . '"'
                );
                $res .= PHP_EOL;
            }
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
     * @todo Remove fallback for XH < 1.6.
     */
    protected function hsc($str)
    {
        return function_exists('XH_hsc')
            ? XH_hsc($str)
            : htmlspeci1alchars($str, ENT_COMPAT, 'UTF-8');
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
        $languages = preg_split('/\r\n|\r|\n/', $pcf['languages_labels']);
        $res = array();
        foreach ($languages as $language) {
            list($key, $value) = explode('=', $language);
            $res[$key] = explode(';', $value);
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
     * @global array The configuration of the plugins.
     */
    protected function languageFlag($language)
    {
        global $pth, $plugin_cf;

        return $pth['folder']['flags'] . $language . '.'
            . $plugin_cf['polyglott']['flags_extension'] . '"';
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
     * @return string (X)HTML.
     */
    public function languageMenu()
    {
        $languages = array();
        foreach ($this->model->otherLanguages() as $language) {
            $href = $this->hsc($this->languageURL($language));
            $src = $this->languageFlag($language);
            $alt = $this->hsc($this->getAltAttribute($language));
            $languages[$language] = compact('href', 'src', 'alt');
        }
        return $this->render('languagemenu', compact('languages'));
    }

    /**
     * Returns the alt attribute for a language flag.
     *
     * @param string $language A language code.
     *
     * @return string
     *
     * @global int The current page index.
     */
    protected function getAltAttribute($language)
    {
        global $s;

        $tag = $this->pageTag($s);
        $labels = $this->languageLabels();
        if (isset($labels[$language])) {
            if ($this->model->hasTag($tag, $language)
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
}

?>
