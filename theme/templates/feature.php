<!--============= Header Section Ends Here =============-->
<section class="page-header bg_img" data-background="assets/home/images/page-header.png">
    <div class="bottom-shape d-none d-md-block">
        <img src="assets/home/css/img/page-header.png" alt="css">
    </div>
    <div class="container">
        <div class="page-header-content cl-white">
            <h2 class="title">
                <?php echo ($bhs == 1) ? 'Fitur' : 'Features';  ?>
            </h2>
        </div>
    </div>
</section>
<!--============= Header Section Ends Here =============-->

<!--============= Feature Section Starts Here =============-->
<section class="feature-section padding-top padding-bottom oh">
    <?php
    $kontenid = '';
    $kontenpage = '';
    $r = generate_modul_bytipe('Feature', array('type_name' => 'Feature', 'tags' => ''), $bhs);
    if ($r) {
        $kontenid = $r->article_id;
        $kontenpage = '<div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-8">
                        <div class="section-header mw-100">
                            <h5 class="cate">' . $r->summary . '</h5>
                            <h2 class="title">' . $r->article_title . '.</h2>
                            ' . $r->content . '
                        </div>
                    </div>
                </div>';
    } else {
        $kontenpage = '<div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="section-header mw-100">
                <h5 class="cate"></h5>
                <h2 class="title"></h2>
                Bahasa tidak tersedia/Language not available
            </div>
        </div>
    </div>';
    }

    $tab = '';
    $arrtab = array();
    $im = '';
    $ik = '';
    $s = generate_related_article('Feature', 'Feature', $where = array(), $bhs, $urutan = 'publish_date');
    if ($s) {
        $x = 0;
        foreach ($s as $rows) {
            $ssub =  generate_related_article('Feature', $rows->article_title, $where = array(), $bhs, $urutan = 'publish_date');
            $arrtab[str_replace(' ', '', strtolower($rows->article_title))] = '<div class="tab-pane ' . (($x == 0) ? 'fade show active' : '') . '" id="' . strtolower($rows->article_title) . '">'
                . '<div class="row">'
                . '<div class="col-lg-6 col-md-10">
                        <div class="feature-tab-header">
                            <h3 class="title">' . $rows->article_title . '</h3>
                            ' . $rows->content . '
                        </div>
                    </div>'
                . '<div class="cola-area">';
            if ($ssub) {
                foreach ($ssub as $rowsub) {
                    $gambar = get_image_article($rowsub->article_id);
                    if ($gambar) {
                        foreach ($gambar as $rowimg) {
                            if ($rowimg->mainimg == 0) {
                                $ik = '<img src="publik/rabmag/image/' . $rowimg->image_path . '" alt="feature">';
                            } else {
                                $im = '<img src="publik/rabmag/image/' . $rowimg->image_path . '" alt="feature">';
                            }
                        }
                    }
                    $arrtab[str_replace(' ', '', strtolower($rows->article_title))] .= '<div class="cola-item">'
                        . '<div class="col-md-6 col-lg-5 col-xl-4">
                        <div class="cola-content">
                            <div class="thumb">
                                ' . $ik . '
                            </div>
                            <span class="cate">' . $rowsub->summary . '</span>
                            <h5 class="title">' . $rowsub->article_title . '</h5>
                            ' . $rowsub->content . '
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-7 col-xl-8">
                        <div class="cola-thumb">
                        ' . $im . '
                        </div>
                    </div>'
                        . '</div>';
                }
            }

            $arrtab[str_replace(' ', '', strtolower($rows->article_title))] .= ''
                . '</div>'
                . '</div></div>';

            $tab .= '<li>
                        <a data-toggle="tab" href="#' . str_replace(' ', '', strtolower($rows->article_title))  . '" class="' . (($x == 0) ? 'active' : '') . '">' . $rows->article_title . '</a>
                    </li>';

            $x++;
        }
    }
    ?>
    <div class="container">
        <?php echo $kontenpage; ?>

        <ul class="nav nav-tabs feature-tab-menu">
            <?php echo $tab; ?>
        </ul>
        <div class="tab-content">
            <?php
            foreach ($arrtab as $kontentab) {
                echo $kontentab;
            }

            ?>
        </div>
    </div>
</section>
<!--============= Feature Section Ends Here =============-->


<!--============= Comunity Section Starts Here =============-->

<!--============= Comunity Section Ends Here =============-->

<!--============= Sponsor Section Section Here =============-->
<section class="sponsor-section padding-bottom">
    <?php
    $k = '';
    $r = $this->Data_model->ambilDataWhere(DB_GALERI, array("cat_id" => 4, 'active' => 1), 'date_modified', 'ASC');
    foreach ($r as $b) :
        // $link = base_url() . 'a/' . bin2hex($b->cat_id) . '/' . generate_title_to_url(trim($b->name)) . '/gsk';
        $k .= '<div class="sponsor-thumb">
                <img src="publik/rabmag/galeri/' . $b->image . '" alt="sponsor">
            </div>';
    endforeach;

    ?>
    <div class="container">
        <div class="section-header mw-100">
            <h5 class="cate">
                <?php echo ($bhs == 1) ? 'Digunakan oleh lebih dari 1.000.000 orang di seluruh dunia' : 'Used by over 1,000,000 people worldwide';  ?>
            </h5>
            <h2 class="title">
                <?php echo ($bhs == 1) ? 'Perusahaan yang mempercayai kami' : 'Companies that trust us';  ?>
            </h2>
        </div>
        <div class="sponsor-slider-4 owl-theme owl-carousel">
            <?php echo $k; ?>
        </div>
    </div>
</section>
<!--============= Sponsor Section Ends Here =============-->