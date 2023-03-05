<?php

/**
 * Copyright 2021 Christoph M. Becker
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

namespace Polyglot;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\HtmlView as View;
use Polyglot\Infra\SystemChecker;

class PluginInfoTest extends TestCase
{
    public function testRendersPluginInfo(): void
    {
        $systemChecker = $this->createStub(SystemChecker::class);
        $systemChecker->method("checkVersion")->willReturn(false);
        $systemChecker->method("checkWritability")->willReturn(false);
        $sut = new PluginInfo(
            "./plugins/polyglot/",
            XH_includeVar("./languages/en.php", "plugin_tx")["polyglot"],
            $systemChecker,
            new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["polyglot"])
        );
        $response = $sut();
        Approvals::verifyHtml($response->output());
    }
}
