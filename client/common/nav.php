<div id="nav" class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand">F2E 自动化页面测试</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li class="<?php if( isset( $pageName ) && $pageName === 'index' ) { ?>active<?php } ?>" ><a href="index.php">首页</a></li>
                    <li class="<?php if( isset( $pageName ) && $pageName === 'docs' ) { ?>active<?php } ?>" ><a href="docs.php">文档中心</a></li>
                    <li class="<?php if( isset( $pageName ) && $pageName === 'about' ) { ?>active<?php } ?>" ><a href="about.php">关于</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>