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
use Plib\Url;
use Polyglot\Infra\Model;
use XH\Pages;

class MainAdminControllerTest extends TestCase
{
    public function testDefaultAction(): void
    {
        $pages = $this->createStub(Pages::class);
        $pages->method("getCount")->willReturn(2);
        $pages->method("level")->willReturnOnConsecutiveCalls(1, 2);
        $pages->method("heading")->willReturnOnConsecutiveCalls("Foo", "Bar");
        $pages->method("url")->willReturnOnConsecutiveCalls("foo", "bar");

        $model = $this->createStub(Model::class);
        $model->method("otherLanguages")->willReturn(["de", "fr"]);
        $model->method("pageTag")->willReturnOnConsecutiveCalls("foo", "bar");
        $model->method("isTranslated")->willReturn(true, false, false, true);
        $model->method("languageURL")->willReturnOnConsecutiveCalls(
            (new Url("http://example.com/", "", "foo-de"))->with("edit"),
            (new Url("http://example.com/", "", "bar-fr"))->with("edit")
        );
       
        $view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["polyglot"]);

        $subject = new MainAdminController($pages, new Url("http://example.com/", "", ""), $model, $view);
        ob_start();
        $subject->defaultAction();
        $response = (string) ob_get_clean();
        Approvals::verifyHtml($response);
    }
}
