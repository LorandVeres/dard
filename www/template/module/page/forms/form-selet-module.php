                    <div class="row_6 center_box">
                        <div class="center">
                            <h2>First step select one module</h2>
                        </div>
                        <p>
                            Before to proced we have to select a module to work on it. If the module is 
                            not in the select list, please go, and <a href="../pages/add-module" target="blank">add one module.</a>
                        </p>
                        
                            <form name="addpage" method="post" action="/pages/add-page">
                                <div class="single_form">
                                    <div class="row_9">
                                        <label for="module_select" class="center">Select a module</label>
                                    </div>
                                    <div class="row_9 center">
                                        <?php $this->slect_box($this->modules, 'name="module" id="module_select" class="spacer_3"',$tag, '', 'name') ?>
                                    </div>
                                    <input type="hidden" name="select_module" value="select_module"/>
                                    <div class="center">
                                        <input type="submit" value="Select module" class="spacer_3" />
                                    </div>
                                </div>
                            </form>
                    </div>