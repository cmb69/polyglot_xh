<?php

use Plib\HtmlView as View;
use Plib\Url;

if (!isset($this)) {
    header("HTTP/1.1 404 Not found");
    exit;
}

/**
 * @var View $this
 * @var array<int,array{hreflang:string,href:Url}> $links
 */
?>
<?php foreach ($links as $link):?>
<link rel="alternate" hreflang="<?=$this->esc($link['hreflang'])?>" href="<?=$this->esc($link['href']->absolute())?>">
<?php endforeach?>
