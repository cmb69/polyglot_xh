<?php

use DG\BypassFinals;

const CMSIMPLE_XH_VERSION = "CMSimple_XH 1.7.5";

require_once "./vendor/autoload.php";
BypassFinals::enable();
require_once "../../cmsimple/classes/PageDataRouter.php";
require_once "../../cmsimple/classes/Pages.php";
require_once "../../cmsimple/functions.php";
require_once "../plib/classes/HtmlView.php";
require_once "../plib/classes/Url.php";
require_once "./classes/AlternateLinkController.php";
require_once "./classes/InfoController.php";
require_once "./classes/LanguageMenuController.php";
require_once "./classes/MainAdminController.php";
require_once "./classes/infra/Model.php";
require_once "./classes/PageDataTabController.php";
require_once "./classes/Plugin.php";
require_once "./classes/infra/SystemChecker.php";
