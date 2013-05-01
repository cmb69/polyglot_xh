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
     * @global string  The current language.
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the core.
     * @access public
     */
    function Polyglott_Controller()
    {
        global $sl, $pth, $cf;

        $this->_model = new Polyglott_Model(
            $sl, $cf['language']['default'], $pth['folder']['base']
        );
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
     * Returns the language menu.
     *
     * @access public
     *
     * @global int  The index of the current page.
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the core.
     * @global array  The page data of the current page.
     * @return string  The (X)HTML.
     */
    function languageMenu()
    {
        global $s, $pth, $cf, $pd_current;

        if ($s >= 0) {
            $tag = isset($pd_current['polyglott_tag'])
                ? $pd_current['polyglott_tag']
                : false;
            $polyglott = $tag ? '?polyglott=' . $tag : '';
        } else {
            $polyglott = '';
        }
        $languages = $this->_languageLabels();
        $o = '';
        foreach ($this->_model->otherLanguages() as $lang) {
            $url = $pth['folder']['base']
                . ($lang == $cf['language']['default'] ? '' : $lang . '/')
                . $polyglott;
            $alt = isset($languages[$lang]) ? $languages[$lang] : $lang;
            $o .= '<a href="' . $url . '">'
                . tag('img src="' . $pth['folder']['flags'] . $lang . '.gif"'
                      . ' alt="' . $alt . '" title="' . $alt . '"')
                . '</a>';
        }
        return $o;
    }
}