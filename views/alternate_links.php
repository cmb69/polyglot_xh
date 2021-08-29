<?php

use Polyglot\View;

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
<link rel="alternate" hreflang="<?=$this->escape($link['hreflang'])?>" href="<?=$this->escape($link['href'])?>">
<?php endforeach?>
