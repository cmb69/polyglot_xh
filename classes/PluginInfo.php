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
use Polyglot\Infra\Response;
use Polyglot\Infra\SystemChecker;

class PluginInfo
{
    /** @var string */
    private $pluginFolder;

    /** @var array<string,string> */
    private $text;

    /**
     * @var SystemChecker
     */
    private $systemChecker;

    /**
     * @var View
     */
    private $view;

    /** @param array<string,string> $text */
    public function __construct(string $pluginFolder, array $text, SystemChecker $systemChecker, View $view)
    {
        $this->pluginFolder = $pluginFolder;
        $this->text = $text;
        $this->systemChecker = $systemChecker;
        $this->view = $view;
    }

    public function __invoke(): Response
    {
        return Response::create($this->view->render('plugin_info', [
            'checks' => $this->getChecks(),
            'version' => POLYGLOT_VERSION,
        ]));
    }

    /**
     * @return array<array{state:string,label:string,state_label:string}>
     */
    public function getChecks(): array
    {
        return [
            $this->checkPhpVersion('7.1.0'),
            $this->checkXhVersion('1.7.0'),
            $this->checkWritability("$this->pluginFolder/css/"),
            $this->checkWritability("$this->pluginFolder/cache/"),
            $this->checkWritability("$this->pluginFolder/config/"),
            $this->checkWritability("$this->pluginFolder/languages/")
        ];
    }

    /**
     * @return array{state:string,label:string,state_label:string}
     */
    private function checkPhpVersion(string $version): array
    {
        $state = $this->systemChecker->checkVersion(PHP_VERSION, $version) ? 'success' : 'fail';
        return [
            "state" => $state,
            "label" => sprintf($this->text['syscheck_phpversion'], $version),
            "state_label" => $this->text["syscheck_$state"],
        ];
    }

    /**
     * @return array{state:string,label:string,state_label:string}
     */
    private function checkXhVersion(string $version): array
    {
        $state = $this->systemChecker->checkVersion(CMSIMPLE_XH_VERSION, "CMSimple_XH $version") ? 'success' : 'fail';
        return [
            "state" => $state,
            "label" => sprintf($this->text['syscheck_xhversion'], $version),
            "state_label" => $this->text["syscheck_$state"],
        ];
    }

    /**
     * @return array{state:string,label:string,state_label:string}
     */
    private function checkWritability(string $folder): array
    {
        $state = $this->systemChecker->checkWritability($folder) ? 'success' : 'warning';
        return [
            "state" => $state,
            "label" => sprintf($this->text['syscheck_writable'], $folder),
            "state_label" => $this->text["syscheck_$state"],
        ];
    }
}
