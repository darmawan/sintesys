<!--============= Header Section Starts Here =============-->
<header class="header-section header-white-dark">
    <div class="container">
        <div class="header-wrapper">
            <div class="logo">
                <a href="index.html">
                    <img src="assets/home/images/logo/logo.png" alt="logo">
                </a>
            </div>
            <ul class="menu">
                <?php
                $tagliinduk = '';
                foreach (generate_top_menu_fr($bhs) as $menu) :
                    $aktifinduk = '';
                    $taglianak = '';
                    $terpilihanak = '';
                    $clslianak = 0;
                    $clsli = ($menu['submenus'] <> FALSE) ? 'dropdown' : '';
                    $clsah = ($menu['submenus'] <> FALSE) ? ' class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : '';

                    //                            $linkinduk = ($menu['parent']->reference_id == 0) ? $menu['parent']->menu_url : $menu['parent']->reference_id;
                    $linkinduk = ($menu['parent']->reference_id == 0) ? base_url($menu['parent']->menu_url) : (parse_link($menu['parent']->type_id, $menu['parent']->reference_id, '', 1));

                    if ($menu['submenus'] != false) :
                        $taglianak .= '<ul class="dropdown-menu dropdown-menu-left">';
                        foreach ($menu['submenus'] as $submenu) :
                            $clslianak = ($submenu['childparent']->reference_id == $smenu) ? $clslianak + 1 : $clslianak;
                            $terpilihanak = ($submenu['childparent']->reference_id == $smenu) ? 'active-link' : '';
                            $linkanak = ($submenu['childparent']->reference_id == 0) ? base_url($submenu['childparent']->menu_url) : (parse_link($submenu['childparent']->type_id, $submenu['childparent']->reference_id, '', 1));
                            //                                    $linkanak = parse_link($submenu['childparent']->type_id, $submenu['childparent']->reference_id, null, 1);
                            $taglianak .= '<li class="' . $terpilihanak . '"><a href="' . $linkanak . '">' . $submenu['childparent']->menu_name . '</a>';
                        endforeach;
                        $taglianak .= '</ul>';
                    endif;
                    $aktifinduk = ($clslianak > 0) ? ' active' : '';
                    $tagliinduk .= '<li class="' . $clsli .  ' ' . $aktifinduk . '"><a href="' . $linkinduk . '" ' . $clsah . '>' . $menu['parent']->menu_name . '</a>';
                    $tagliinduk = $tagliinduk . $taglianak . '</li>';
                endforeach;
                echo $tagliinduk . '</ul>';
                ?>
            </ul>
            <div class="header-right">
                <!-- <select class="select-bar" onchange="location = this.value;"> -->
                <select class="select-bar bhs">
                    <option value="1" <?php echo ($bhs==1)? 'selected="selected"':''; ?>>ID</option>
                    <option value="2" <?php echo ($bhs==2)? 'selected="selected"':''; ?>>EN</option>
                </select>
            </div>
            <div class="header-bar d-lg-none">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="header-right">

                <a href="https://demo.sintesys.id" class="header-button d-none d-sm-inline-block">Sintesys Demo</a>
            </div>
        </div>
    </div>

</header>
<!--============= Header Section Ends Here =============-->