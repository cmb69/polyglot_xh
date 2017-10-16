<?php

/**
 * The model class of Polyglott_XH.
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

namespace Polyglott;

/**
 * The model class.
 *
 * @category CMSimple_XH
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class Model
{
    /**
     * The current language.
     *
     * @var string
     */
    protected $language;

    /**
     * The default language.
     *
     * @var string
     */
    protected $defaultLanguage;

    /**
     * The relative path of the base folder.
     *
     * @var string
     */
    protected $baseFolder;

    /**
     * The relative path of the data folder.
     *
     * @var string
     */
    protected $dataFolder;

    /**
     * The handle of the lock file.
     *
     * @var resource
     */
    protected $lockHandle;

    /**
     * The polyglott tags.
     *
     * @var array
     */
    protected $tags;

    /**
     * Initializes a new instance.
     *
     * @param string $language    The current langage.
     * @param string $defaultLang The default language.
     * @param string $baseFolder  The relative path of the base folder.
     * @param string $dataFolder  The relative path of the data folder.
     */
    public function __construct($language, $defaultLang, $baseFolder, $dataFolder)
    {
        $this->language = (string) $language;
        $this->defaultLanguage = (string) $defaultLang;
        $this->baseFolder = (string) $baseFolder;
        $this->dataFolder = (string) $dataFolder;
    }

    /**
     * Returns the path of the tags file.
     *
     * @return string
     */
    public function tagsFile()
    {
        return $this->dataFolder . 'tags.dat';
    }

    /**
     * Returns all available languages.
     *
     * @return array
     */
    public function languages()
    {
        $languages = XH_secondLanguages();
        $languages[] = $this->defaultLanguage;
        sort($languages);
        return $languages;
    }

    /**
     * Returns all available languages other than the current one.
     *
     * @return array
     */
    public function otherLanguages()
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
     */
    public function lastMod()
    {
        $filename = $this->tagsFile();
        return file_exists($filename)
            ? filemtime($filename)
            : 0;
    }

    /**
     * Reads the cache file and returns whether that succeeded.
     *
     * @param bool $needsUpdate If tags file has to be updated.
     *
     * @return bool
     */
    public function init($needsUpdate)
    {
        $lockFile = $this->lockFile();
        if (!touch($lockFile)) {
            return false;
        }
        $this->lockHandle = fopen($lockFile, 'r');
        flock($this->lockHandle, $needsUpdate ? LOCK_EX : LOCK_SH);
        $filename = $this->tagsFile();
        $contents = is_readable($filename)
            ? file_get_contents($filename)
            : false;
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
     * Returns the path of the lock file.
     *
     * @return string
     */
    protected function lockFile()
    {
        return $this->dataFolder . '.lck';
    }

    /**
     * Updates the cache file for the current language
     * and returns whether that succeeded.
     *
     * @param array $pageData The page data of all pages.
     * @param array $urls     The URLs of the pages.
     *
     * @return bool
     */
    public function update($pageData, $urls)
    {
        foreach ($pageData as $i => $data) {
            if (!empty($data['polyglott_tag'])) {
                $tag = $data['polyglott_tag'];
                $this->tags[$tag][$this->language] = $urls[$i];
            }
        }
        $contents = serialize($this->tags);
        $ok = (bool) file_put_contents($this->tagsFile(), $contents);
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
     */
    public function languageURL($language, $tag)
    {
        $res = $this->getInstallationUrl();
        if ($language != $this->defaultLanguage) {
            $res .= $language . '/';
        }
        if (isset($this->tags[$tag][$language])) {
            $res .= '?' . $this->tags[$tag][$language];
        }
        return $res;
    }

    /**
     * Returns the URL of the CMSimple installation.
     *
     * @return string
     *
     * @global string The script name.
     */
    protected function getInstallationUrl()
    {
        return preg_replace(
            array('/index\.php$/', '/(?<=\/)' . $this->language . '\/$/'),
            '',
            CMSIMPLE_URL
        );
    }

    /**
     * Returns whether a page is translated in a certain language.
     *
     * @param string $tag      The polyglott tag.
     * @param string $language The language code.
     *
     * @return bool
     */
    public function isTranslated($tag, $language)
    {
        return isset($this->tags[$tag][$language]);
    }
}
