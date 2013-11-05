<?php

/**
 * The model class of Polyglott_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Polyglott
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2013 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */

/**
 * The model class.
 *
 * @category CMSimple_XH
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class Polyglott_Model
{
    /**
     * The current language.
     *
     * @var string
     *
     * @access protected
     */
    var $language;

    /**
     * The default language.
     *
     * @var string
     *
     * @access protected
     */
    var $defaultLanguage;

    /**
     * The relative path of the base folder.
     *
     * @var string
     *
     * @access protected
     */
    var $baseFolder;

    /**
     * The relative path of the data folder.
     *
     * @var string
     *
     * @access protected
     */
    var $dataFolder;

    /**
     * The handle of the lock file.
     *
     * @var resource
     *
     * @access protected
     */
    var $lockHandle;

    /**
     * The polyglott tags.
     *
     * @var array
     *
     * @access protected
     */
    var $tags;

    /**
     * Initializes a newly created instance.
     *
     * @param string $language        The current langage.
     * @param string $defaultLanguage The default language.
     * @param string $baseFolder      The relative path of the base folder.
     * @param string $dataFolder      The relative path of the data folder.
     *
     * @access public
     */
    function Polyglott_Model($language, $defaultLanguage, $baseFolder, $dataFolder)
    {
        $this->language = $language;
        $this->defaultLanguage = $defaultLanguage;
        $this->baseFolder = $baseFolder;
        $this->dataFolder = $dataFolder;
    }

    /**
     * Returns the path of the lock file.
     *
     * @return string
     *
     * @access protected
     */
    function lockFile()
    {
        return $this->dataFolder . '.lck';
    }

    /**
     * Returns the path of the tags file.
     *
     * @return string
     *
     * @access public
     */
    function tagsFile()
    {
        return $this->dataFolder . 'tags.dat';
    }

    /**
     * Returns all available languages.
     *
     * @return array
     *
     * @access public
     */
    function languages()
    {
        $languages = array($this->defaultLanguage);
        $dh = opendir($this->baseFolder);
        while (($dir = readdir($dh)) !== false) {
            if (preg_match('/^[A-z]{2}$/', $dir)) {
                $languages[] = $dir;
            }
        }
        return $languages;
    }

    /**
     * Returns all available languages other than the current one.
     *
     * @return array
     *
     * @access public
     */
    function otherLanguages()
    {
        $res = array();
        $languages = $this->languages();
        foreach ($languages as $language) {
            if ($language != $this->language) {
                $res[] = $language;
            }
        }
        return $res;
    }

    /**
     * Returns the timestamp of the last modification of the cache file.
     *
     * @return int
     *
     * @access public
     */
    function lastMod()
    {
        $res = filemtime($this->tagsFile());
        return $res;
    }

    /**
     * Reads the cache file and returns whether that succeeded.
     *
     * @param bool $needsUpdate If tags file has to be updated.
     *
     * @return bool
     *
     * @access public
     */
    function init($needsUpdate)
    {
        $lockFile = $this->lockFile();
        if (!touch($lockFile)) {
            return false;
        }
        $this->lockHandle = fopen($lockFile, 'r');
        flock($this->lockHandle, $needsUpdate ? LOCK_EX : LOCK_SH);
        $contents = file_get_contents($this->tagsFile());
        if ($contents === false) {
            $contents = serialize(array());
            if (!file_put_contents($this->tagsFile(), $contents)) {
                return false;
            }
        }
        $this->tags = unserialize($contents);
        if (!$needsUpdate) {
            flock($this->lockHandle, LOCK_UN);
            fclose($this->lockHandle);
        }
        return $this->tags !== false;

    }

    /**
     * Updates the cache file for the current language
     * and returns whether that succeeded.
     *
     * @param array $pageData The page data of all pages.
     * @param array $urls     The URLs of the pages.
     *
     * @return bool
     *
     * @access public
     */
    function update($pageData, $urls)
    {
        foreach ($pageData as $i => $data) {
            if (!empty($data['polyglott_tag'])) {
                $tag = $data['polyglott_tag'];
                $this->tags[$tag][$this->language] = $urls[$i];
            }
        }
        $contents = serialize($this->tags);
        $ok = !!file_put_contents($this->tagsFile(), $contents);
        flock($this->lockHandle, LOCK_UN);
        fclose($this->lockHandle);
        return $ok;
    }

    /**
     * Returns the URL to another language.
     *
     * @param string $language The language to link to.
     * @param string $tag      The polyglott tag.
     *
     * @return string
     *
     * @access public
     */
    function languageURL($language, $tag)
    {
        $res = $this->baseFolder;
        if ($language != $this->defaultLanguage) {
            $res .= $language . '/';
        }
        if (isset($this->tags[$tag][$language])) {
            $res .= '?' . $this->tags[$tag][$language];
        }
        return $res;
    }

    /**
     * Returns whether a page is translated in a certain language.
     *
     * @param string $tag      The polyglott tag.
     * @param string $language The language code.
     *
     * @return bool
     *
     * @access public
     */
    function isTranslated($tag, $language)
    {
        return isset($this->tags[$tag][$language]);
    }
}

?>
