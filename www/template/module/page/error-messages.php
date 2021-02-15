<?php
include_once '../module/page/runtime-msg.Class.php';
$runMsg = new runMessages($myPage, $tag);

$myPage->ifNoAjaxTop($tag);

?>
                <div class="template">
                </div>
                <div id="content">
                	<?php //include_once 'template/layout/main/top-sticker.php'; ?>
                	<header class="row section group">
							<div class="left top">
								<input type="button" class="sub-menu-h-i left" />
								<h1 class="left spacer_3">Runtime messages <span></span></h1>
							</div>
							<div class="search_box livesearch spacer_1">
								<input id="searchbox" type="search" size="30" onkeyup="searchPageId(this.value)" placeholder="search page">
                            	<div id="searchlinks">
                            	</div>
							</div>
					</header>
                	<div class="left sub-menu">
                		<div class="sub-menu-h">
                			<h3 class="menu-header-title"><span class="pagename"><?php echo $runMsg->param_pagename; ?></span></h3>
                			<div class="template">
                				<span id="pageid"><?php echo $runMsg->param_pageid; ?></span>
                				<span id="moduleid"><?php echo $runMsg->param_moduleid; ?></span>
                			</div>
                		</div>
                		<div class="sub-menu-b">
                			<div class="menu-section spacer_3">
                				<div class="menu-section-title">
                					page
                				</div>
                				<div class="spacer_3">
                					<p><a href="javascript:;" onclick="addpageid(this, 'add')" title="add message per page">Add per page</a></p>
                					<p><a href="javascript:;" onclick="addpageid(this, 'view')" title="view message per page">View per page</a></p>
                				</div>
                			</div>
                			<div class="menu-section spacer_5">
                				<div class="menu-section-title">
                					module
                				</div>
                				<p><a href="#" onclick="addmoduleid(this, 'view')" title="view and edit message per module if page is chosen">View per module</a></p>
                			</div>
                		</div>
                	</div>
                	<div class="content left">
						<div class="wrap">
							<?php 
								if(!empty($runMsg->param_pagename)) echo '<p>Page name: <span class="pagename">'.$runMsg->param_pagename . '</span></p>'."\n";
								if(!empty($runMsg->param_modulename)) echo '<p>Module : <span class="modulename">'.$runMsg->param_modulename .'</span></p>'."\n";
								$runMsg->html_wrap_errors($tag); 
								$runMsg->job_control($myPage, $tag);
							?>
						</div>
						
					</div>
                </div>
<?php $myPage->ifNoAjaxBottom(); ?>