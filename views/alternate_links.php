<?php

use Plib\HtmlView as View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var array<int,array{hreflang:string,href:string}> $links
 */
?>
<!-- polyglot alternate links -->
<?foreach ($links as $link):?>
<link rel="alternate" hreflang="<?=$this->esc($link['hreflang'])?>" href="<?=$this->esc($link['href'])?>">
<?endforeach?>
