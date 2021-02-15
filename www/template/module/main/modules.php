<?php
include_once '../module/main/modules.Class.php';
$dard_modules = new modules($myPage, $tag);
$myPage->ifNoAjaxTop($tag);

?>

			<div id="content">
				<div class="template">
				</div>
				<header class="row section group">
					<div class="left top">
						<h1 class="left spacer_3">Modules <span></span></h1>
					</div>
					<div class="search_box livesearch spacer_1">
						<input id="searchbox" type="search" size="30" onkeyup="searchPageId(this.value)" placeholder="search page">
						<div id="searchlinks">
						</div>
					</div>
				</header>
<?php $dard_modules -> print_modules_combo_boxes($myPage, $tag); ?>
			</div>
<?php $myPage -> ifNoAjaxBottom(); ?>
