                <nav id="menu" class="menu slideout-menu left">
                    <section class="menu-header">
                        <h3 class="menu-header-title"><strong>Main Menu</strong><span class="close-menu" onclick="showHideMenu();"></span></h3>
                    </section>
                    <section class="menu-section">
                        <h4 class="menu-section-title" onclick="hideMenuList(this);">Live pages<span class="ham-button"></span></h4>
                        <ul class="menu-section-list">
                            <li><a href="<?php mylink('/') ?>">home</a></li>
                            <li><a href="/test">test</a></li>
                        </ul>
                    </section>
                    <section class="menu-section">
                        <h4 class="menu-section-title" onclick="hideMenuList(this);">Pages admin<span class="ham-button"></span></h4>
                        <ul class="menu-section-list">
                            <li><a href="<?php mylink('pages') ?>">pages</a></li>
                            <li><a href="<?php mylink('pages/add-page') ?>">add page</a></li>
                            <li><a href="<?php mylink('pages/user-privilege') ?>">user privilege</a></li>
                            <li><a href="<?php mylink('pages/error-messages') ?>">error messages</a></li>
                            <li><a href="<?php mylink('pages/meta-tags') ?>">meta tags</a></li>
                            <li><a href="<?php mylink('pages/modules') ?>">modules</a></li>
                            <li><a href="<?php mylink('pages/regex-checker') ?>">regex-checker</a></li>
                        </ul>
                    </section>
                    <section class="menu-section">
                        <h4 class="menu-section-title" onclick="hideMenuList(this);">Users account<span class="ham-button"></span></h4>
                        <ul class="menu-section-list">
                            <li><a href="<?php mylink('users') ?>">users</a></li>
                            <li><a href="<?php mylink('login') ?>">login</a></li>
                            <li><a href="<?php mylink('logout') ?>">logout</a></li>
                            <li><a href="<?php mylink('register') ?>">register</a></li>
                        </ul>
                    </section>
                </nav>
