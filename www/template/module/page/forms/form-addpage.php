                        <div class="row_6 center_box">
                            <div class="center">
                                <h2>You are adding a new page in to the <mark><?php echo $this->module_name; ?></mark> module</h2>
                            </div>
                        <p>
                            Before to proced we have to select a module to work on it. If the module is 
                            not in the select list, please go, and <a href="../pages/add-module">add one module.</a>
                        </p>
                        <div>
                            <?php $this->html_wrap_errors($config, $DBconect, $tag); ?>
                        </div>
                            <form name="addpage" method="post" action="/pages/add-page">
                                <div class="section group">
                                    <div class="col half">
                                        <div class="form_row">
                                            <div>
                                                <label for="pagename">Page name </label>
                                            </div>
                                            <div>
                                                <input class="spacer_1" type="text" name="pagename" id="pagename" required="required"/>
                                            </div>
                                        </div>
                                        <div class="form_row">
                                            <div>
                                                <label for="title">Title</label>
                                            </div>
                                            <div>
                                                <input class="spacer_1" type="text" name="title" id="title" required="required"/>
                                            </div>
                                        </div>
                                        <div class="form_row">
                                            <div>
                                                <label for="pageURI">file-name.php</label>
                                            </div>
                                            <div>
                                                <input class="spacer_1" type="text" name="pageURI" id="pageURI" required="required"/>
                                            </div>
                                        </div>
                                        <div class="form_row">
                                            <div>
                                            </div>
                                            <div>
                                                <input type="hidden" name="module_id" value="<?php $this->module_id ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col half">
                                        <div class="form_row">
                                            <div>
                                                <label for="type">Page type</label>
                                            </div>
                                            <div>
                                                <select name="type" class="spacer_1" id="type">
                                                    <option value="main">Main page</option>
                                                    <option value="sub">Sub page</option>
                                                    <option value="top">Top page</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form_row">
                                            <div>
                                                <label for="arg">Have arguments?</label>
                                            </div>
                                            <div>
                                                <select name="arg" id="arg" class="spacer_1">
                                                    <option value="0">NO</option>
                                                    <option value="1">YES</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form_row">
                                            <div>
                                                <label for="parentpage">Parent page if known</label>
                                            </div>
                                            <div>
                                                <?php 
                                                        $this->slect_box(
                                                        $this->mainPages, 
                                                        'name="parentpage" id="parentpage" class="spacer_1"',
                                                        $tag, 
                                                        array('id' => 'NULL', 'pagename' => 'Default to NULL'), 
                                                        'pagename');
                                                ?>
                                            </div>
                                        </div>
                                         <div class="form_row">
                                            <div>
                                                <label for="css">Css file</label>
                                            </div>
                                            <div>
                                                <?php 
                                                    $this->slect_box(
                                                        $this->css, 
                                                        'name="css" id="css" class="spacer_1"', 
                                                        $tag, 
                                                        array('id' => 'NULL', 'href' => 'Default to NULL'), 
                                                        'href'); 
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                        
                                </div>
                                <input type="hidden" name="add_page" value="add" />
                                <div class="center spacer_3">
                                    <input type="submit" value="Add page" />
                                </div>
                            </form>
                            <div>
                                <div>
                                    <h3>FAQ</h3>
                                </div>
                                <div>
                                    <p>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 
                                    </p>
                                    <p>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 
                                    </p>
                                    <p>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 
                                    </p>
                                </div>
                            </div>
                        </div>