<?php

/**
 * Back-end of Polyglott_XH.
 *
 * @package    Polyglott
 * @copyright  Copyright (c) 2012-2013 Christoph M. Becker <http://3-magi.net/>
 * @license    http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version    $Id$
 * @link       http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */


/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/**
 * Register the page data tab.
 */
$pd_router->add_tab(
    'Polyglott',
    $pth['folder']['plugins'] . 'polyglott/polyglott_view.php'
);


/**
 * Handle the plugin administration.
 */
if (isset($polyglott) && $polyglott == 'true') {
    $o .= print_plugin_admin('on');
    switch ($admin) {
	case '':
	    $o .= $_Polyglott->_info();
	    break;
	case 'plugin_main':
	    $o .= $_Polyglott->_administration();
	    break;
	default:
	    $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
