<?php

const CMSIMPLE_XH_VERSION = "CMSimple_XH 1.7.5";
const CMSIMPLE_URL = "http://example.com/";
const POLYGLOT_VERSION = "1.0";

require_once "./vendor/autoload.php";

require_once "../../cmsimple/classes/PageDataRouter.php";
require_once "../../cmsimple/classes/Pages.php";
require_once "../../cmsimple/functions.php";
require_once "../plib/classes/HtmlView.php";
require_once "../plib/classes/Url.php";

spl_autoload_register(function (string $className) {
    $parts = explode("\\", $className);
    if ($parts[0] !== "Polyglot") {
        return;
    }
    if (count($parts) === 3) {
        $parts[1] = strtolower($parts[1]);
    }
    $filename = implode("/", array_slice($parts, 1));
    if (is_readable("./classes/$filename.php")) {
        include_once "./classes/$filename.php";
    } elseif (is_readable("./tests/$filename.php")) {
        include_once "./tests/$filename.php";
    }
});
