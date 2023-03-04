<?php

/**
 * Copyright 2012-2021 Christoph M. Becker
 *
 * This file is part of Polyglot_XH.
 *
 * Polyglot_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Polyglot_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Polyglot_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

use Polyglot\Dic;
use Polyglot\Infra\Request;
use XH\PageDataRouter;

if (!defined("CMSIMPLE_XH_VERSION")) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

const POLYGLOT_VERSION = "1.0beta2";

function Polyglot_languageMenu(): string
{
    return Dic::makeLanguageMenuController()->defaultAction(Request::current());
}

/**
 * @var PageDataRouter $pd_router
 */

$pd_router->add_interest("polyglot_tag");

Dic::makeAlternateLinkController()->defaultAction(Request::current());
