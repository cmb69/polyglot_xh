<?php

class Polyglott_Controller
{
    /**
     * The model instance.
     *
     * @access private
     *
     * @var object
     */
    var $_model;

    /**
     * Construct a controller instance.
     *
     * @access public
     *
     * @global bool  Whether the user is logged in as admin.
     * @global string  The current language.
     * @global array  The paths of system files and folders.
     * @global array  The "URLs" of the pages.
     * @global array  The configuration of the core.
     * @global object  The page data router.
     * @access public
     */
    function Polyglott_Controller()
    {
        global $adm, $sl, $pth, $u, $cf, $pd_router;

        $dataFolder = $pth['folder']['plugins'] . 'polyglott/data/';
        $this->_model = new Polyglott_Model(
            $sl, $cf['language']['default'], $pth['folder']['base'],
            $dataFolder
        );
        $contentLastMod = filemtime($pth['file']['content']);
        $pageDataLastMod = filemtime($pth['file']['pagedata']);
        $tagsLastMod = $this->_model->lastMod();
        $needsUpdate = $tagsLastMod < max($contentLastMod, $pageDataLastMod);
        if ($this->_model->init($needsUpdate)) {
            if ($needsUpdate) {
                if (!$this->_model->update($pd_router->find_all(), $u)) {
                    e('cntsave', 'file', $this->_model->tagsFile());
                }
            }
        } else {
            e('cntopen', 'file', $this->_model->tagsFile());
        }
        if ($adm) {
            $pd_router->add_tab(
                'Polyglott',
                $pth['folder']['plugins'] . 'polyglott/polyglott_view.php'
            );
            $this->_dispatch();
        }
    }

    /**
     * Dispatches according to request.
     *
     * @access private
     *
     * @global string  The value of the GET/POST parameter 'admin'.
     * @global string  The value of the GET/POST parameter 'action'.
     * @return void
     */
    function _dispatch()
    {
        global $admin, $action, $o, $polyglott;

        if (isset($polyglott) && $polyglott == 'true') {
            $o .= print_plugin_admin('on');
            switch ($admin) {
                case '':
                    $o .= $this->_info();
                    break;
                case 'plugin_main':
                    $o .= $this->_administration();
                    break;
                default:
                    $o .= plugin_admin_common($action, $admin, 'polyglott');
            }
        }
    }

    /**
     * Returns a string with special HTML characters escaped.
     *
     * @access private
     *
     * @param  string $str
     * @return string
     */
    function _hsc($str)
    {
        return htmlspecialchars($str, ENT_COMPAT, 'UTF_8');
    }

    /**
     * Renders a template.
     *
     * @access private
     *
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the core.
     * @param  string $_template  The name of the template.
     * @param  array $_bag  Variables available in the template.
     * @return string
     */
    function _render($_template, $_bag)
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
     * @access private
     *
     * @global array  The paths of system files and folders.
     * @global array  The localization of the core.
     * @global array  The localization of the plugins.
     * @return array
     */
    function _systemChecks() // RELEASE-TODO
    {
        global $pth, $tx, $plugin_tx;

        $ptx = $plugin_tx['polyglott'];
        $phpVersion = '4.0.7';
        $checks = array();
        $checks[sprintf($ptx['syscheck_phpversion'], $phpVersion)] =
            version_compare(PHP_VERSION, $phpVersion) >= 0 ? 'ok' : 'fail';
        foreach (array('pcre') as $ext) {
            $checks[sprintf($ptx['syscheck_extension'], $ext)] =
                extension_loaded($ext) ? 'ok' : 'fail';
        }
        $checks[$ptx['syscheck_magic_quotes']] =
            !get_magic_quotes_runtime() ? 'ok' : 'fail';
        $checks[$ptx['syscheck_encoding']] =
            strtoupper($tx['meta']['codepage']) == 'UTF-8' ? 'ok' : 'warn';
        $folders = array();
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'polyglott/' . $folder;
        }
        foreach ($folders as $folder) {
            $checks[sprintf($ptx['syscheck_writable'], $folder)] =
                is_writable($folder) ? 'ok' : 'warn';
        }
        return $checks;
    }

    /**
     * Returns the plugin information view.
     *
     * @access private
     *
     * @global array  The paths of system files and folders.
     * @global array  The localization of the plugins.
     * @return string  The (X)HTML.
     */
    function _info()
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
        $checks = $this->_systemChecks();
        $icon = $pth['folder']['plugins'] . 'polyglott/polyglott.png';
        $version = POLYGLOTT_VERSION;
        $bag = compact(
            'labels', 'images', 'checks', 'icon', 'version'
        );
        return $this->_render('info', $bag);
    }

    /**
     * Returns a dictionary from lanugage codes to labels.
     *
     * @access private
     *
     * @global array  The configuration of the plugins.
     * @return array
     */
    function _languageLabels()
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
     * @access private
     *
     * @global array  The paths of system files and folders.
     * @param  string $language  The language code.
     * @return string
     */
    function _languageFlag($language)
    {
        global $pth;

        $res = $pth['folder']['flags'] . $language . '.gif"';
        return $res;
    }

    /**
     * Returns a polyglott tag.
     *
     * @access private
     *
     * @global object  The page data router.
     * @param  int $index  The index of the page.
     * @return string
     */
    function _pageTag($index)
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
     * @access private
     *
     * @global int  The index of the current page.
     * @param  string $language  A language code.
     * @return string
     */
    function _languageURL($language)
    {
        global $s;

        $tag = $s > 0 ? $this->_pageTag($s) : null;
        $res = $this->_model->languageURL($language, $tag);
        return $res;
    }

    /**
     * Returns the main administration view.
     *
     * @global string  The script name.
     * @global int  The number of pages.
     * @global array  The headings of the pages.
     * @global array  The levels of the pages.
     * @global array  The "URLs" of the pages.
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the core.
     * @global object  The page data router.
     * @return string  The (X)HTML.
     */
    function _administration()
    {
        global $sn, $cl, $h, $l, $u, $pth, $cf, $pd_router;

        $languages = $this->_model->otherLanguages();
        $pages = array();
        for ($i = 0; $i < $cl; $i++) {
            $heading = $h[$i];
            $url = $sn . '?' . $u[$i];
            $indent = $l[$i] - 1;
            $tag = $this->_pageTag($i);
            $translations = array();
            foreach ($languages as $language) {
                $translations[$language] =
                    $this->_model->isTranslated($tag, $language)
                    ? $this->_model->languageURL($language, $tag)
                    : null;
            }
            $pages[] = compact('heading', 'url', 'indent', 'tag', 'translations');
        }
        $bag = compact('languages', 'pages');
        return $this->_render('admin', $bag);
    }

    /**
     * Returns the language menu.
     *
     * @access public
     *
     * @return string  The (X)HTML.
     */
    function languageMenu()
    {
        $labels = $this->_languageLabels();
        $languages = array();
        foreach ($this->_model->otherLanguages() as $language) {
            $href = $this->_languageURL($language);
            $src = $this->_languageFlag($language);
            $alt = isset($labels[$language]) ? $labels[$language] : $language;
            $languages[$language] = compact('href', 'src', 'alt');
        }
        return $this->_render('languagemenu', compact('languages'));
    }
}
