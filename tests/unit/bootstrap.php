<?php

use DG\BypassFinals;

require_once "./vendor/autoload.php";
BypassFinals::enable();
require_once "../../cmsimple/classes/PageDataRouter.php";
require_once "../../cmsimple/classes/Pages.php";
require_once "../../cmsimple/functions.php";
require_once "../plib/classes/HtmlView.php";
require_once "./classes/AlternateLinkController.php";
require_once "./classes/InfoController.php";
require_once "./classes/LanguageMenuController.php";
require_once "./classes/MainAdminController.php";
require_once "./classes/Model.php";
require_once "./classes/PageDataTabController.php";
require_once "./classes/Plugin.php";
require_once "./classes/SystemCheckService.php";
