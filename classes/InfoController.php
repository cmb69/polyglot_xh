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
use Polyglot\Infra\SystemChecker;

class InfoController
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

    public function defaultAction(): string
    {
        return $this->view->render('info', [
            'checks' => $this->getChecks(),
            'version' => POLYGLOT_VERSION,
        ]);
    }

    /**
     * @return array<array{state:string,label:string,stateLabel:string}>
     */
    public function getChecks(): array
    {
        return [
            $this->checkPhpVersion('7.0.0'),
            $this->checkXhVersion('1.7.0'),
            $this->checkWritability("$this->pluginFolder/css/"),
            $this->checkWritability("$this->pluginFolder/cache/"),
            $this->checkWritability("$this->pluginFolder/config/"),
            $this->checkWritability("$this->pluginFolder/languages/")
        ];
    }

    /**
     * @return array{state:string,label:string,stateLabel:string}
     */
    private function checkPhpVersion(string $version): array
    {
        $state = $this->systemChecker->checkVersion(PHP_VERSION, $version) ? 'success' : 'fail';
        $label = sprintf($this->text['syscheck_phpversion'], $version);
        $stateLabel = $this->text["syscheck_$state"];
        return compact('state', 'label', 'stateLabel');
    }

    /**
     * @return array{state:string,label:string,stateLabel:string}
     */
    private function checkXhVersion(string $version): array
    {
        $state = $this->systemChecker->checkVersion(CMSIMPLE_XH_VERSION, "CMSimple_XH $version") ? 'success' : 'fail';
        $label = sprintf($this->text['syscheck_xhversion'], $version);
        $stateLabel = $this->text["syscheck_$state"];
        return compact('state', 'label', 'stateLabel');
    }

    /**
     * @return array{state:string,label:string,stateLabel:string}
     */
    private function checkWritability(string $folder): array
    {
        $state = $this->systemChecker->checkWritability($folder) ? 'success' : 'warning';
        $label = sprintf($this->text['syscheck_writable'], $folder);
        $stateLabel = $this->text["syscheck_$state"];
        return compact('state', 'label', 'stateLabel');
    }
}
