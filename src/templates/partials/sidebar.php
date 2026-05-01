<?php $arrSidebarData = getSidebarMenu(1);?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <?php
        if(isset($arrSidebarData) && $arrSidebarData != false){
            foreach($arrSidebarData as $data){
                ?>
                <li class="nav-item">
                    <?php $url = rtrim($site_url, '/') . '/' . ltrim($data['end_points'], '/');?>
                    <a class="nav-link" href="<?php echo $url;?>">
                        <i class="mdi <?php echo $data['sidebar_icon'];?> menu-icon"></i>
                        <span class="menu-title"><?php echo $data['sidebar_title'];?></span>
                    </a>
                </li>
                <?php
            }
        }
        ?>
    </ul>
</nav>
