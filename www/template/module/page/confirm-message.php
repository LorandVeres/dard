                        <div class="row center_box">
                            <div class="center">
                                <h2><?php echo $this->page_confirm_h2; ?></h2>
                            </div>
                            <div class="row_6 center_box">
                                <?php 
                                    $this->html_wrap_errors($config, $DBconect, $tag); 
                                    $this->wrapConfirm($config, $DBconect, $tag);
                                ?>
                                
                            </div>
                        </div>