<!-- tempat menampung konten -->
<!--============= Banner Section Starts Here =============-->
<?php
$this->load->view(TEMPLATE . 'banner_konten');
?>
<!--============= Banner Section Ends Here =============-->


<!--============= Amazing Feature Section Starts Here =============-->
<section class="amazing-feature-section padding-top padding-bottom pos-rel" id="feature">
    <?php
    $kontenamazingfeature = '';
    foreach (generate_modul('Section Amazing Feature', 'artikel', array(), $bhs) as $ba) :
        $link = base_url() . 'a/' . bin2hex($ba->article_id) . '/' . generate_title_to_url(trim($ba->article_title));
        $kontenamazingfeature = '<div class="section-header mw-100">
                            <h5 class="cate">' . $ba->article_title . '</h5>
                            ' . trim($ba->content) . '
                        </div>';
    endforeach;

    $kontenamazingfeaturesub = '';
    foreach (generate_modul('Section Amazing Feature Sub', 'artikel', array(), $bhs, 'tags') as $ba) :
        $doc = new DOMDocument();
        $doc->loadHTML($ba->content);
        $xpath = new DOMXPath($doc);
        $src = $xpath->evaluate("string(//img/@src)");
        // $link = base_url() . 'a/' . bin2hex($ba->article_id) . '/' . generate_title_to_url(trim($ba->article_title));
        $kontenamazingfeaturesub .= ' <div class="col-sm-6 col-lg-3">
        <div class="am-item">
            <div class="am-thumb">
                <img src="' . $src . '" alt="feature">
            </div>
            <div class="am-content">
                <h5 class="title">' . $ba->article_title . '</h5>
            </div>
        </div>
    </div>';
    endforeach;
    ?>

    <div class="ft-shape">
        <img src="assets/home/images/feature/globe2.png" alt="feature">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <?php echo $kontenamazingfeature; ?>
            </div>
            <div class="col-12">
                <div class="row justify-content-center mb-30-none">
                    <?php echo $kontenamazingfeaturesub; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Amazing Feature Section Ends Here =============-->


<!--============= Counter Section Starts Here =============-->
<section class="counter-section bg_img oh pos-rel" data-background="assets/home/images/bg/counter-bg.png">
    <?php
    $kontencounter = '';
    foreach (generate_modul('Section Counter', 'artikel', array(), $bhs) as $ba) :
        $link = base_url() . 'a/' . bin2hex($ba->article_id) . '/' . generate_title_to_url(trim($ba->article_title));
        $kon = explode('|', $ba->summary);
        $kontencounter = '<div class="section-header left-style cl-white">
                            <h5 class="cate">' . trim($kon[0]) . '</h5>
                            <h2 class="title">' . $ba->article_title . '</h2>
                            <p>' . trim($kon[1]) . '</p>
                        </div>
                        <div class="counter-area">' . $ba->content . '</div>';
    endforeach;
    ?>
    <div class="elem-1">
        <img src="assets/home/images/counter/circle1.png" alt="counter">
    </div>
    <div class="elem-2">
        <img src="assets/home/images/counter/round.png" alt="counter">
    </div>
    <div class="elem-3">
        <img src="assets/home/images/counter/square.png" alt="counter">
    </div>
    <div class="elem-4">
        <img src="assets/home/images/counter/square2.png" alt="counter">
    </div>
    <div class="elem-5">
        <img src="assets/home/images/counter/tera.png" alt="counter">
    </div>
    <div class="elem-6">
        <img src="assets/home/images/counter/tri1.png" alt="counter">
    </div>
    <div class="elem-7">
        <img src="assets/home/images/counter/tri2.png" alt="counter">
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="counter-wrapper padding-bottom padding-top">
                    <?php echo  $kontencounter; ?>
                    <!-- <div class="section-header left-style cl-white">
                        <h5 class="cate">Super Clean User Interface</h5>
                        <h2 class="title">Discover Valuable Insights About Your Market.</h2>
                        <p>Explore app of the next generation.The only app you’ll need to power your life.</p>
                    </div>
                    <div class="counter-area">
                        <div class="counter--item">
                            <div class="counter-thumb">
                                <img src="assets/home/images/icon/counter3.png" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="counter">17501</span></h2>
                                <span>Premium User</span>
                            </div>
                        </div>
                        <div class="counter--item">
                            <div class="counter-thumb">
                                <img src="assets/home/images/icon/counter4.png" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="counter">1,987</span></h2>
                                <span>Daily Visitors</span>
                            </div>
                        </div>
                        <div class="counter--item">
                            <div class="counter-thumb">
                                <img src="assets/home/images/icon/counter4.png" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="counter">1,987</span></h2>
                                <span>Daily Visitors</span>
                            </div>
                        </div>
                        <div class="counter--item">
                            <div class="counter-thumb">
                                <img src="assets/home/images/icon/counter4.png" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="counter">1,987</span></h2>
                                <span>Daily Visitors</span>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="counter-thumb-1">
                    <img src="assets/home/images/counter/phn1.png" alt="counter">
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Counter Section Ends Here =============-->

<!--============= Feature Section Starts Here =============-->
<section class="feature-section padding-top padding-bottom oh pos-rel">
    <?php
    $kontenfeature = '';
    try {
        foreach (generate_modul('Section Feature', 'artikel', array(), $bhs) as $ba) :
            $link = base_url() . 'a/' . bin2hex($ba->article_id) . '/' . generate_title_to_url(trim($ba->article_title));
            $kontenfeature = '<div class="section-header mw-725">
                                <h5 class="cate">' . $ba->summary . '</h5>
                                <h2 class="title">' . $ba->article_title . '</h2>
                                ' . $ba->content . '
                            </div>';
        endforeach;
    } catch (\Throwable $th) {
        $kontenfeature = '<div class="section-header mw-725">
        <h5 class="cate"></h5>
        <h2 class="title"></h2>
        Bahasa tidak tersedia/Language not available
    </div>';
    }


    $arrimgutama  = array();
    $kontenfeaturesub = '';
    $x = 0;
    try {
        foreach (generate_modul('Section Feature Sub', 'artikel', array(), $bhs, 'tags') as $ba) :
            $img_konten = $this->Data_model->satuData(DB_IMAGE, array('reffid' => $ba->article_id, 'mainimg' => 0));
            if ($img_konten) {
                $image = ($img_konten->image_path == '') ? 'assets/home/img/glosika.jpg' : 'publik/rabmag/image/' . $img_konten->image_path;
            } else {
                $image = 'assets/home/img/glosika.jpg';
            }
            $kontenfeaturesub .= '<div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="' . $image . '" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">' . $ba->article_title . '</h4>
                            ' . $ba->content . '
                        </div>
                    </div>';
            $img_kontenutama = $this->Data_model->satuData(DB_IMAGE, array('reffid' => $ba->article_id, 'mainimg' => 1));
            $arrimgutama[] = $img_kontenutama->image_path;
            $x++;
        endforeach;

        $kontenfeaturethumb = '';
        if ($arrimgutama) {
            for ($x = 0; $x < count($arrimgutama); $x++) {
                $kontenfeaturethumb .= '<div class="main-thumb">
                                        <img src="publik/rabmag/image/' . $arrimgutama[$x] . '" alt="feature">
                                    </div>';
            }
        }
    } catch (\Throwable $th) {
        $kontenfeaturesub .= '<div class="feature-item">
        <div class="feature-thumb">
            <div class="thumb">
                
            </div>
        </div>
        <div class="feature-content">
            <h4 class="title"></h4>
            Bahasa tidak tersedia/Language not available
        </div>
    </div>';
        $kontenfeaturethumb = '<div class="main-thumb">
    
</div>';
    }


    // function imgna($kode)
    // {
    //     $img_konten = $this->Data_model->selectData(DB_IMAGE, 'mainimg', array('reffid' => $kode), 'desc');
    //     foreach ($img_konten as $rowimg) {
    //         if ($img_konten->mainimg == 1) {
    //             $arrimgutama[] = '<div class="main-thumb">
    //                             <img src="publik/rabmag/image/' . $img_konten->image_path . '" alt="feature">
    //                         </div>';
    //         } else {
    //         }
    //     }
    //     if ($img_konten) {
    //         $image = ($img_konten->image_path == '') ? 'assets/home/img/glosika.jpg' : 'publik/rabmag/image/' . $img_konten->image_path;
    //     } else {
    //         $image = 'assets/home/img/glosika.jpg';
    //     }
    // }
    ?>
    <div class="feature-shapes d-none d-lg-block">
        <img src="assets/home/images/feature/feature-shape.png" alt="feature">
    </div>
    <div class="container">
        <?php echo $kontenfeature; ?>
        <div class="row">
            <div class="col-lg-5 rtl">
                <div class="feature--thumb pr-xl-4 ltr">
                    <div class="feat-slider owl-carousel owl-theme" data-slider-id="1">
                        <?php echo $kontenfeaturethumb; ?>
                        <!-- <div class="main-thumb">
                            <img src="assets/home/images/feature/pro-main2.png" alt="feature">
                        </div>
                        <div class="main-thumb">
                            <img src="assets/home/images/feature/pro-main.png" alt="feature">
                        </div>
                        <div class="main-thumb">
                            <img src="assets/home/images/feature/pro-main3.png" alt="feature">
                        </div>
                        <div class="main-thumb">
                            <img src="assets/home/images/feature/pro-main4.png" alt="feature">
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="feature-wrapper mb-30-none owl-thumbs" data-slider-id="1">
                    <?php echo  $kontenfeaturesub; ?>
                    <!-- <div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="assets/home/images/feature/pro1.png" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Increase Productivity & Performance</h4>
                            <p>Forget about paper checklists, spreadsheets and time-consuming reports. Your salesperson
                                can complete their work digitally. At HQ you get a real-time overview in your dashboard.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="assets/home/images/feature/pro2.png" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Realtime Data</h4>
                            <p>The satisfaction of users is the most important and the focus is on usability and
                                completeness</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="assets/home/images/feature/pro3.png" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Easy to Manage Your All Data</h4>
                            <p>The satisfaction of users is the most important and the focus is on usability and
                                completeness</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="assets/home/images/feature/pro4.png" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Offline Mode</h4>
                            <p>Work anywhere with offline mode and keep your teams moving—even in areas of low connectivity.</p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Feature Section Ends Here =============-->

<!--============= Coverage Section Starts Here =============-->
<section class="coverage-section padding-top padding-bottom pb-max-lg-0" id="coverage">
    <?php
    $x = 0;
    $kontencoverage = array();
    $star = '';
    try {
        foreach (generate_modul('Section Coverage', 'artikel', array(), $bhs) as $ba) :
            $link = base_url() . 'a/' . bin2hex($ba->article_id) . '/' . generate_title_to_url(trim($ba->article_title));
            if ($x == 0) {
                $summary = explode('|', $ba->summary);
                for ($y = 0; $y < intval(trim($summary[1])); $y++) {
                    $star .= '<span><i class="fas fa-star"></i></span>';
                }
                $kontencoverage[$x] = '<div class="col-lg-7">
                                <div class="section-header left-style coverage-header">
                                    <h5 class="cate">' . $summary[0] . '</h5>
                                    <h2 class="title">' . $ba->article_title . '</h2>
                                   ' . $ba->content . '
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="coverage-right-area text-lg-right">
                                    <div class="rating-area">
                                        <div class="ratings">' . $star . '</div>
                                        <span class="average">' . trim($summary[1]) . '.0 / 5.0</span>
                                    </div>
                                    <h2 class="amount">' . trim($summary[2]) . '</h2>
                                    <a href="#0">Total User Reviews <i class="fas fa-paper-plane"></i></a>
                                </div>
                            </div>';
            } else {
                $kontencoverage[$x] = $ba->content;
            }

            $x++;
        endforeach;
    } catch (\Throwable $th) {
        //throw $th;
    }


    ?>
    <div class="container">
        <div class="row align-items-center">
            <?php echo (isset($kontencoverage[0])) ? $kontencoverage[0] : ''; ?>
            <!-- <div class="col-lg-7">
                <div class="section-header left-style coverage-header">
                    <h5 class="cate">Our stats say more than any words</h5>
                    <h2 class="title">Apps Without Borders</h2>
                    <p>
                        Sintesys app are growing by 300% every year with a steady love from users around the world.
                        We are also close to achieving 10 million cumulative downloads.
                    </p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="coverage-right-area text-lg-right">
                    <div class="rating-area">
                        <div class="ratings">
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                        </div>
                        <span class="average">5.0 / 5.0</span>
                    </div>
                    <h2 class="amount">312,921+</h2>
                    <a href="#0">Total User Reviews <i class="fas fa-paper-plane"></i></a>
                </div>
            </div> -->
        </div>
        <div class="coverage-wrapper bg_img" data-background="assets/home/images/bg/id-map.png">
            <?php echo (isset($kontencoverage[1])) ? $kontencoverage[1] : ''; ?>
            <!-- <div class="border-item-1">
                <span class="name">Sumatera</span>
                <h2 class="title">70.7%</h2>
            </div>
            <div class="border-item-2">
                <span class="name">Papua</span>
                <h2 class="title">14.4%</h2>
            </div>
            <div class="border-item-3">
                <span class="name">Sulawesi</span>
                <h2 class="title">8.4%</h2>
            </div>
            <div class="border-item-4">
                <span class="name">Java</span>
                <h2 class="title">1.8%</h2>
            </div>
            <div class="border-item-5">
                <span class="name">Borneo</span>
                <h2 class="title">1.8%</h2>
            </div>
            <div class="border-item-6">
                <span class="name">Bali</span>
                <h2 class="title">3%</h2>
            </div> -->
        </div>
    </div>
</section>
<!--============= Coverage Section Ends Here =============-->

<!--============= Custom-Plan Section Starts Here =============-->
<section class="custom-plan bg_img oh" data-background="assets/home/images/bg/line-bg.png">
    <?php
    $kontencustomplan = '';
    try {
        foreach (generate_modul('Section Custom-Plan', 'artikel', array(), $bhs) as $ba) :
            $link = base_url() . 'a/' . bin2hex($ba->article_id) . '/' . generate_title_to_url(trim($ba->article_title));
            $result = preg_replace('/<p\b[^>]*>(.*?)<\/p>/', '', $ba->content);
            $kontencustomplan = '<div class="section-header cl-white"><p>' . $ba->content . '</p><i>' . $ba->summary . '</i></div>';
        endforeach;
    } catch (\Throwable $th) {
        $kontencustomplan = '<div class="section-header cl-white"><p>Bahasa tidak tersedia/Language not available</p><i></i></div>';
    }

    ?>
    <div class="container">
        <div class="custom-wrapper">
            <span class="circle"></span>
            <span class="circle two"></span>
            <div class="calculate-bg">
                <img src="assets/home/images/bg/calculate-bg.png" alt="bg">
            </div>
            <div class="custom-area">
                <?php echo $kontencustomplan; ?>
            </div>
        </div>
    </div>
</section>
<!--============= Custom-Plan Section Ends Here =============-->