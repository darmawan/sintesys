<?php
$arrelemen = array('elem-3', 'elem-1', "elem-4", 'elem-2', 'elem-5', 'elem-6', 'elem-7', 'top-left d-none d-lg-block"');
$slider = '';
$slider2 = '';
$h = 0;
foreach (generate_modul('', 'slider') as $baris) :
    if ($baris->image <> '') {
        $img = DIR_PUBLIK . 'slider/' . $baris->image;
        $teks = str_replace('<p>', '<p class="pogoSlider-slide-element hidde" data-in="slideRight" data-out="slideUp" data-duration="750" data-delay="900">', $baris->description);
        if ($baris->imagepos == 'center') {
            $slider .= '<div class="' . $arrelemen[$h] . '"><img src="' . $img . '" alt="counter"></div>';
            $h++;
        } else {
            $slider2 .= '<div class="banner-thumb"><img src="' . $img . '"></div>';
        }
    }
endforeach;

$banner = generate_modul_bykategori('banner', array('cat_name' => 'Banner'));

?>

<section class="banner-2 oh">
    <div class="banner-bg-2 bg_img" data-background="assets/home/images/banner/banner-2.png"></div>
    <?php echo $slider; ?>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="banner-content-2">
                    <h1 class="title"><?php echo ($banner) ? $banner->article_title : ''; ?></h1>

                    <?php echo $banner->content; ?>
                    <!-- <h1 class="title">Sales Intelligent System</h1>
                    <p>
                        Real-time insights on retail execution and sales performance. Smarter merchandising,
                        promotion, and sales execution tools for your field team.
                    </p>
                    <div class="banner-button-group">
                        <a href="contact.html" class="button-4">Schedule A Demo</a>
                        <a href="feature.html" class="button-4 active">Explore Features</a>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-5">
                <div class="banner-1-slider-wrapper">
                    <div class="banner-1-slider owl-carousel owl-theme">
                        <?php echo $slider2; ?>
                    </div>
                    <div class="ban-click two">
                        <div class="arrow">
                            <img class="d-none d-lg-block" src="assets/home/images/banner/arrow.png" alt="banner">
                            <img class="d-lg-none" src="assets/home/images/banner/arrow2.png" alt="banner">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>