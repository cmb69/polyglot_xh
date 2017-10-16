<?php

/**
 * Copyright 2012-2017 Christoph M. Becker
 *
 * This file is part of Polyglott_XH.
 *
 * Polyglott_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Polyglott_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Polyglott_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Polyglott;

class Model
{
    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $defaultLanguage;

    /**
     * @var string
     */
    private $baseFolder;

    /**
     * @var string
     */
    private $dataFolder;

    /**
     * @var resource
     */
    private $lockHandle;

    /**
     * @var array
     */
    private $tags;

    /**
     * @param string $language
     * @param string $defaultLang
     * @param string $baseFolder
     * @param string $dataFolder
     */
    public function __construct($language, $defaultLang, $baseFolder, $dataFolder)
    {
        $this->language = (string) $language;
        $this->defaultLanguage = (string) $defaultLang;
        $this->baseFolder = (string) $baseFolder;
        $this->dataFolder = (string) $dataFolder;
    }

    /**
     * @return string
     */
    public function tagsFile()
    {
        return $this->dataFolder . 'tags.dat';
    }

    /**
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
     * @param bool $needsUpdate
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
     * @return string
     */
    private function lockFile()
    {
        return $this->dataFolder . '.lck';
    }

    /**
     * @return bool
     */
    public function update(array $pageData, array $urls)
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
     * @param string $language
     * @param string $tag
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
     * @return string
     */
    private function getInstallationUrl()
    {
        return preg_replace(
            array('/index\.php$/', '/(?<=\/)' . $this->language . '\/$/'),
            '',
            CMSIMPLE_URL
        );
    }

    /**
     * @param string $tag
     * @param string $language
     * @return bool
     */
    public function isTranslated($tag, $language)
    {
        return isset($this->tags[$tag][$language]);
    }
}
