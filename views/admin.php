<h1>Polyglott – <?=$this->text('label_translations')?></h1>
<div class="polyglott_translations">
    <table>
        <thead>
            <tr>
                <th><?=$this->text('label_page')?></th>
                <th><?=$this->text('label_tag')?></th>
<?php foreach ($this->languages as $language):?>
                <th><?=$language?></th>
<?php endforeach?>
            </tr>
        </thead>
        <tbody>
<?php foreach ($this->pages as $page):?>
            <tr>
                <td>
                    <a href="<?=$page['url']?>" style="padding-left: <?=$page['indent']?>em"><?=$page['heading']?></a>
                </td>
                <td><?=$page['tag']?></td>
<?php   foreach ($page['translations'] as $translation):?>
                <td>
<?php       if (isset($translation)):?>
                    <a href="<?=$translation?>"><?=$this->text('label_ok')?></a>
<?php       endif?>
                </td>
<?php   endforeach?>
            </tr>
<?php endforeach?>
        </tbody>
    </table>
</div>
