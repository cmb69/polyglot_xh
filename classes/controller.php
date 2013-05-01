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
}
