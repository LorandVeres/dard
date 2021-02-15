<?php $dard->ifNoAjaxTop($tag); ?>

			<div id="content">
				<div class="template">
				</div>
				<header class="row section group">
					<div class="left top">
						<h1 class="left spacer_3">Dashboard <span></span></h1>
					</div>
					<div class="search_box livesearch spacer_1">
						<input id="searchbox" type="search" size="30" onkeyup="searchPageId(this.value)" placeholder="search page">
						<div id="searchlinks">
						</div>
					</div>
				</header>

			</div>
<?php $dard->ifNoAjaxBottom(); ?>