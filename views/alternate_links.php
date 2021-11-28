<?php

use Plib\HtmlView as View;

if (!isset($this)) {
    header("HTTP/1.1 404 Not found");
    exit;
}

/**
 * @var View $this
 * @var array<int,array{hreflang:string,href:string}> $links
 */
?>
<?php foreach ($links as $link):?>
<link rel="alternate" hreflang="<?=$this->esc($link['hreflang'])?>" href="<?=$this->esc($link['href'])?>">
<?php endforeach?>
