<?php
include_once '../module/main/modules.Class.php';
$dard_modules = new modules($dard, $tag);
$dard->ifNoAjaxTop($tag);

?>

			<div id="content">
				<div class="template">
				</div>
				<header class="row section group">
					<h1 class="col spacer_3"><a href="/modules">Modules</a></h1>
					<a href="modules?a=add_module" class="left head-link">Add Module</a>
					<a href="modules?a=import_module" class="left head-link" onclick="alert('Importing functionality not yet implemented')">Import Module</a>
					<div class="search_box livesearch spacer_1">
						<input id="searchbox" type="search" size="30" onkeyup="searchPageId(this.value)" placeholder="search page">
						<div id="searchlinks">
						</div>
					</div>
				</header>
<?php $dard_modules -> print_modules_combo_boxes($dard, $tag); ?>
			
			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec qu </p>
			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec qu </p>
			</div>
<?php $dard -> ifNoAjaxBottom(); ?>
