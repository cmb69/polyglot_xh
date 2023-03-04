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
use Polyglot\Infra\FakePages;
use Polyglot\Infra\FakeRequest;
use Polyglot\Infra\Model;

class MainAdminControllerTest extends TestCase
{
    public function testDefaultAction(): void
    {
        $model = $this->createStub(Model::class);
        $model->method("languages")->willReturn(["de", "en", "fr"]);
        $model->method("pageTag")->willReturnOnConsecutiveCalls("foo", "bar");
        $model->method("isTranslated")->willReturn(true, false, false, true);
        $model->method("languageURL")->willReturnOnConsecutiveCalls(
            (new Url("http://example.com/", "", "foo-de"))->with("edit"),
            (new Url("http://example.com/", "", "bar-fr"))->with("edit")
        );
       
        $view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["polyglot"]);

        $subject = new MainAdminController(new FakePages, $model, $view);
        $response = $subject->defaultAction(new FakeRequest(["sl" => "en", "defaultLanguage" => "en"]));
        Approvals::verifyHtml($response);
    }
}
