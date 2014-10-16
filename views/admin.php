<?php $this->preventAccess()?>
<!-- Polyglott_XH: administration -->
<h1>Polyglott &ndash; <?php echo $lang['label_translations'];?></h1>
<div class="polyglott_translations">
    <table>
        <thead>
            <tr>
                <th><?php echo $lang['label_page'];?></th>
                <th><?php echo $lang['label_tag'];?></th>
<?php foreach ($languages as $language):?>
                <th><?php echo $language;?></th>
<?php endforeach;?>
            </tr>
        </thead>
        <tbody>
<?php foreach ($pages as $page):?>
            <tr>
                <td>
                    <a href="<?php echo $page['url'];?>" style="padding-left: <?php echo $page['indent'];?>em"><?php echo $page['heading'];?></a>
                </td>
                <td><?php echo $page['tag'];?></td>
<?php foreach ($page['translations'] as $translation):?>
                <td>
<?php if (isset($translation)):?>
                    <a href="<?php echo $translation;?>"><?php echo $lang['label_ok'];?></a>
<?php endif;?>
                </td>
<?php endforeach;?>
            </tr>
<?php endforeach;?>
        </tbody>
    </table>
</div>
