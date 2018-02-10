<?php
include_once '../module/page/pages.Class.php';


$setPage = new pages($config, $DBconect, $myPage, $tag);

$myPage->ifNoAjaxTop();
?>
                <div class="template" style="display: none;">
                    <div id="box" class="search_box">
                        <div id="livesearch" class="single_form">
                            <input id="searchbox" type="search" size="30" onkeyup="searchPageId(this.value)" placeholder="search page">
                            <div id="searchlinks">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="content">
                    <header>
                        <div class="row_9 center_box">
                            <h1 class="center row spacer_3">Set up runtime messages</h1>
                        </div>
                    </header>
                    <div>
                        <?php //$setPage->html_wrap_errors($config, $DBconect, $tag); ?>
                    </div>
                    <?php //$setPage->includeAddForm($config, $DBconect, $tag); ?>
                    <div class="row_6 center_box">
                        <input type="button" id="search" value="search page id" />
                    </div>
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 
                    </p>
                </div>
                    <?php //$setPage->search($myPage); ?>
<?php $myPage->ifNoAjaxBottom(); ?>