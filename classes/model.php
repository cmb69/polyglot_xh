<?php

class Polyglott_Model
{
    /**
     * The current language.
     *
     * @access private
     *
     * @var string
     */
    var $_language;

    /**
     * The default language.
     *
     * @access private
     *
     * @var string
     */
    var $_defaultLanguage;

    /**
     * The relative path of the base folder.
     *
     * @access private
     *
     * @var string
     */
    var $_baseFolder;

    /**
     * The relative path of the data folder.
     *
     * @access private
     *
     * @var string
     */
    var $_dataFolder;

    /**
     * The handle of the lock file.
     *
     * @access private
     *
     * @var resource
     */
    var $_lockHandle;

    /**
     * The polyglott tags.
     *
     * @access private
     *
     * @var array
     */
    var $_tags;

    /**
     * Construct a model instance.
     *
     * @access public
     *
     * @param  string $language  The current langage.
     * @param  string $defaultLanguage  The default language.
     * @param  string $baseFolder  The relative path of the base folder.
     * @param  string $dataFolder  The relative path of the data folder.
     */
    function Polyglott_Model($language, $defaultLanguage, $baseFolder, $dataFolder)
    {
        $this->_language = $language;
        $this->_defaultLanguage = $defaultLanguage;
        $this->_baseFolder = $baseFolder;
        $this->_dataFolder = $dataFolder;
    }

    /**
     * Returns the path of the lock file.
     *
     * @access private
     *
     * @return string
     */
    function _lockFile()
    {
        return $this->_dataFolder . '.lck';
    }

    /**
     * Returns the path of the tags file.
     *
     * @access public
     *
     * @return string
     */
    function tagsFile()
    {
        return $this->_dataFolder . 'tags.dat';
    }

    /**
     * Returns all available languages.
     *
     * @access private
     *
     * @return array
     */
    function _languages()
    {
        $languages = array($this->_defaultLanguage);
        $dh = opendir($this->_baseFolder);
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
     * @access public
     *
     * @return array
     */
    function otherLanguages()
    {
        $res = array();
        $languages = $this->_languages();
        foreach ($languages as $language) {
            if ($language != $this->_language) {
                $res[] = $language;
            }
        }
        return $res;
    }

    /**
     * Returns the timestamp of the last modification of the cache file.
     *
     * @access public
     *
     * @return int
     */
    function lastMod()
    {
        $res = filemtime($this->tagsFile());
        return $res;
    }

    /**
     * Reads the cache file and returns whether that succeeded.
     *
     * @access public
     *
     * @param  bool $needsUpdate  If tags file has to be updated.
     * @return bool
     */
    function init($needsUpdate)
    {
        $lockFile = $this->_lockFile();
        if (!touch($lockFile)) {
            return false;
        }
        $this->_lockHandle = fopen($lockFile, 'r');
        flock($this->_lockHandle, $needsUpdate ? LOCK_EX : LOCK_SH);
        $contents = file_get_contents($this->tagsFile());
        if ($contents === false) {
            $contents = serialize(array());
            if (!file_put_contents($this->tagsFile(), $contents)) {
                return false;
            }
        }
        $this->_tags = unserialize($contents);
        if (!$needsUpdate) {
            flock($this->_lockHandle, LOCK_UN);
            fclose($this->_lockHandle);
        }
        return $this->_tags !== false;

    }

    /**
     * Updates the cache file for the current language
     * and returns whether that succeeded.
     *
     * @access public
     *
     * @param  array $pageData  The page data of all pages.
     * @param  array $urls  The "URLs" of the pages.
     * @return bool
     */
    function update($pageData, $urls)
    {
        foreach ($pageData as $i => $data) {
            if (!empty($data['polyglott_tag'])) {
                $tag = $data['polyglott_tag'];
                $this->_tags[$tag][$this->_language] = $urls[$i];
            }
        }
        $contents = serialize($this->_tags);
        $ok = !!file_put_contents($this->tagsFile(), $contents);
        flock($this->_lockHandle, LOCK_UN);
        fclose($this->_lockHandle);
        return $ok;
    }

    /**
     * Returns the URL to another language.
     *
     * @access public
     *
     * @param  string $language  The language to link to.
     * @param  string $tag  The polyglott tag.
     * @return string
     */
    function languageURL($language, $tag)
    {
        $res = $this->_baseFolder;
        if ($language != $this->_defaultLanguage) {
            $res .= $language . '/';
        }
        if (isset($this->_tags[$tag][$language])) {
            $res .= '?' . $this->_tags[$tag][$language];
        }
        return $res;
    }
}

?>
