<?php
include_once '../module/page/pages.Class.php';


$setPage = new pages($config, $DBconect, $myPage, $tag);

$myPage->ifNoAjaxTop();
?>
                <div id="content">
                    <header>
                        <div class="row_9 center_box">
                            <h1 class="center row spacer_3">You are about to set up a page manualy</h1>
                        </div>
                    </header>
                    <div>
                        <?php $setPage->html_wrap_errors($config, $DBconect, $tag); ?>
                    </div>
                    <?php //$setPage->includeAddForm($config, $DBconect, $tag); ?>
                    <div>
                        
                    </div>
                </div>
<?php $myPage->ifNoAjaxBottom(); ?>