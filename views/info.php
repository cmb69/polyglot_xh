<h1>Polyglott</h1>
<img src="<?=$this->icon()?>" alt="Plugin Icon"
     style="float: left; width: 128px; height: 128px; margin-right: 16px"/>
<p>Version: <?=$this->version()?></p>
<p>Copyright © 2012-2017 <a href="http://3-magi.net/">Christoph M. Becker</a></p>
<p style="text-align: justify">This program is free software: you can
redistribute it and/or modify it under the terms of the GNU General Public
License as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.</p>
<p style="text-align: justify">This program is distributed in the hope that it
will be useful, but <em>without any warranty</em>; without even the implied
warranty of <em>merchantability</em> or <em>fitness for a particular
purpose</em>. See the GNU General Public License for more details.</p>
<p style="text-align: justify">You should have received a copy of the GNU
General Public License along with this program. If not, see
<a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>
<h4><?=$this->text('syscheck_title')?></h4>
<?php foreach ($this->checks as $check => $state):?>
<?=XH_message($state, $check)?>
<?php endforeach?>
