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
use Plib\HtmlView as View;
use Plib\Url;
use Polyglot\Infra\Model;

class LanguageMenuControllerTest extends TestCase
{
    public function testDefaultAction(): void
    {
        $urls = [
            "de" => new Url("http://example.com/", "de", ""),
            "fr" => new Url("http://example.com/", "fr", ""),
            "it" => new Url("http://example.com/", "it", ""),
        ];

        $model = $this->createStub(Model::class);
        $model->method("otherLanguages")->willReturn(["de", "fr", "it"]);
        $model->method("pageTag")->willReturn("foo");
        $model->method("languageURL")->willReturnOnConsecutiveCalls(
            $urls["de"],
            $urls["fr"],
            $urls["it"]
        );

        $view = $this->createMock(View::class);
        $view->expects($this->once())->method("render")->with(
            $this->equalTo("languagemenu"),
            $this->equalTo([
                "languages" => [
                    "de" => [
                        "href" => $urls["de"],
                        "src" => "de.png",
                        "alt" => "nicht übersetzt",
                    ],
                    "fr" => [
                        "href" => $urls["fr"],
                        "src" => "fr.png",
                        "alt" => "français",
                    ],
                    "it" => [
                        "href" => $urls["it"],
                        "src" => "it.png",
                        "alt" => "it",
                    ],
                ]
            ])
        );

        $subject = new LanguageMenuController("", "png", "de=Deutsch;nicht übersetzt\rfr=français", 1, $model, $view);
        $subject->defaultAction();
    }
}
