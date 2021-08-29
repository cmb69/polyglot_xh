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

use PHPUnit\Framework\TestCase;

class AlternateLinkControllerTest extends TestCase
{
    public function testDefaultAction()
    {
        global $cf;
        
        $cf['language']['default'] = "en";
        $model = $this->createStub(Model::class);
        $model->method("languages")->willReturn(["en", "de"]);
        $model->method("isTranslated")->willReturn(true);
        $model->method("languageURL")->willReturn("http://example.com/");
        $view = $this->createMock(View::class);
        $view->expects($this->once())->method("render")->with(
            $this->equalTo("alternate_links"),
            $this->equalTo([
                "links" => [
                    ["hreflang" => "x-default", "href" => "http://example.com/"],
                    ["hreflang" => "en", "href" => "http://example.com/"],
                    ["hreflang" => "de", "href" => "http://example.com/"],
                ],
            ])
        );
        $subject = new AlternateLinkController($model, $view);
        $subject->defaultAction();
    }
}
