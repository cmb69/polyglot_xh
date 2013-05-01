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
     * Construct a model.
     *
     * @access public
     *
     * @param  string $language  The current langage.
     * @param  string $defaultLanguage  The default language.
     * @param  string $baseFolder  The relative path of the base folder.
     */
    function Polyglott_Model($language, $defaultLanguage, $baseFolder)
    {
        $this->_language = $language;
        $this->_defaultLanguage = $defaultLanguage;
        $this->_baseFolder = $baseFolder;
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

}


?>
