<?php
include_once '../module/page/pages.Class.php';


$setPage = new pages($config, $DBconect, $myPage, $tag);

$myPage->ifNoAjaxTop($tag);
?>
                <div id="content">
                    <header>
                        <div class="row_9 center_box">
                            <h1 class="center row spacer_1">You are about to set up a page manualy</h1>
                        </div>
                    </header>
                    <div>
                        <?php 
                            $setPage->html_wrap_errors($config, $DBconect, $tag);
                            $setPage->includeAddForm($config, $DBconect, $tag);
                        ?>
                    </div>
                </div>
<?php $myPage->ifNoAjaxBottom(); ?>
