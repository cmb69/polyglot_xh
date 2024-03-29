<?php

/**
 * Copyright 2012-2023 Christoph M. Becker
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
use Polyglot\Infra\Repository;
use Polyglot\Infra\Request;
use Polyglot\Infra\Response;

class AlternateLinks
{
    /** @var array<string,string> */
    private $conf;

    /** @var Repository */
    private $repository;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        Repository $repository,
        View $view
    ) {
        $this->conf = $conf;
        $this->repository = $repository;
        $this->view = $view;
    }

    public function __invoke(Request $request): Response
    {
        $links = [];
        $translation = $this->repository->findTranslationByPage($request->s());
        foreach ($this->repository->allLanguages() as $language) {
            if ($translation->pageUrl($language) !== null) {
                $links = array_merge($links, $this->alternateLinksFor($request, $language, $translation->tag()));
            }
        }
        return Response::create("")->addHjs($this->view->render('alternate_links', ["links" => $links,]));
    }

    /**
     * @return array<int,array{hreflang:string,href:string}>
     */
    private function alternateLinksFor(Request $request, string $language, string $tag): array
    {
        $result = [];
        $pageUrl = $this->repository->findTranslationByTag($tag)->pageUrl($language);
        $href = $request->url()->lang($language != $this->conf["language_default"] ? $language : "")
            ->page($pageUrl !== null ? $pageUrl : "")->absolute();
        if ($language === $this->conf["language_default"]) {
            $result[] = ["hreflang" => "x-default", "href" => $href];
        }
        $result[] = ["hreflang" => $language, "href" => $href];
        return $result;
    }
}
