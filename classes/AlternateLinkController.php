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

namespace Polyglot;

use Plib\HtmlView as View;
use Plib\Url;
use Polyglot\Infra\LanguageRepo;
use Polyglot\Infra\Model;
use Polyglot\Infra\Request;
use Polyglot\Infra\TranslationRepo;

class AlternateLinkController
{
    /** @var string */
    private $defaultLanguage;

    /** @var View */
    private $view;

    /** @var LanguageRepo */
    private $languageRepo;

    /** @var TranslationRepo */
    private $translationRepo;

    public function __construct(
        string $defaultLanguage,
        View $view,
        LanguageRepo $languageRepo,
        TranslationRepo $translationRepo
    ) {
        $this->defaultLanguage = $defaultLanguage;
        $this->view = $view;
        $this->languageRepo = $languageRepo;
        $this->translationRepo = $translationRepo;
    }

    /**
     * @return void
     */
    public function defaultAction(Request $request)
    {
        global $hjs;

        $this->translationRepo->init($request->sl());
        $links = [];
        $translation = $this->translationRepo->findByPage($request->s());
        foreach ($this->languageRepo->all() as $language) {
            if ($translation->pageUrl($language) !== null) {
                $links = array_merge($links, $this->alternateLinksFor($request, $language, $translation->tag()));
            }
        }
        $hjs .= $this->view->render('alternate_links', compact('links'));
    }

    /**
     * @return array<int,array{hreflang:string,href:Url}>
     */
    private function alternateLinksFor(Request $request, string $language, string $tag): array
    {
        $result = [];
        $pageUrl = $this->translationRepo->findByTag($tag)->pageUrl($language);
        $href = $request->url()->lang($language != $this->defaultLanguage ? $language : "")
            ->page($pageUrl !== null ? $pageUrl : "");
        if ($language === $this->defaultLanguage) {
            $result[] = ["hreflang" => "x-default", "href" => $href];
        }
        $result[] = ["hreflang" => $language, "href" => $href];
        return $result;
    }
}
