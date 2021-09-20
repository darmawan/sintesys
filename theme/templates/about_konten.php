<!-- Page Title Section START -->
<?php
    $div = '<div class="page-title-section" style="background-image: url(#1);"><div class="container"><div class="page-title center-holder"><h1>#2</h1><ul><li><a href="#3">Home</a></li><li><a href="javascript:;">#2</a></li></ul></div></div></div>';
    echo generete_page_title_section(array(base_url('assets/home/img/bg/bg-1.jpg'),$content->article_title,base_url()),$div);
?>
<!-- Page Title Section END -->

<!-- About Company Section START -->
<div class="section-block">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <img src="<?php echo base_url(); ?>publik/rabmag/image/<?php echo ($img_konten->image_path == '') ? '' : $img_konten->image_path; ?>" alt="<?php echo ($img_konten->image_name == '') ? '' : $img_konten->image_name; ?>" class="border-round img-shadow full-width">
            </div>		
            <div class="col-md-5 col-sm-12 col-xs-12 col-md-offset-1">
                <div class="section-heading left-holder mt-20">
                    <h2>We are creative company</h2>
                    <!-- Tabs Start -->
                    <div class='tabs tabs_animate left-holder' style="max-height: 265px;min-height: 265px;">
                        <ul class="tab-menu left-holder mt-20">
                            <li><a href="#tab-1">Who We are</a></li>
                            <li><a href="#tab-2">Our Goal</a></li>
                        </ul>
                        <!-- Tab 1 Start -->
                        <div id='tab-1'>
                            <div class="text-content">
                                <?php echo constant('NM_DESKRIPSI_PT'); ?>
                            </div>
                        </div>
                        <!-- Tab 1 End -->
                        <!-- Tab 2 Start -->
                        <div id='tab-2'>
                            <div class="text-content">
                                <?php echo constant('NM_GOAL_PT'); ?>
                            </div>
                        </div>
                        <!-- Tab 2 End -->
                    </div>    
                    <!-- Tabs End -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="content">
                    <h1 class="mb-25"><i class="icon-dartboard"></i> <?php echo $content->article_title; ?></h1>
                    <!--<p class="mt-25"></p>-->
                    <div class="blog-post">
                        <?php echo $content->content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Company Section END -->



<!-- Services START -->
<div class="section-block-grey">
    <div class="container">
        <div class="section-heading center-holder">
            <h2>What <?php echo $content->article_title; ?> do</h2>
        </div>	
        <?php
        $div = '<article class="service-article clearfix"><div class="article-icon"><i class="icon-idea"></i></div><div class="article-text"><h3>#4</h3><p>#5</p><a href="#2">Read More</a></div></article>';
        echo '<div class="owl-carousel owl-theme" id="articles-services">';
        echo genereate_modul_artikel($content->article_title, 'layanan', array('inpo' => 'induk'), $div);
        echo '</div>';
        ?>          											               
    </div>
</div>
<!-- Services END -->


<!-- Parallax Section START -->
<!--<div class="section-block-parallax" style="background-image: url(http://localhost/sintesys/assets/home/img/bg/bg-3.jpg);">
    <div class="container">
        <div class="section-heading white-color center-holder">
            <h1>Vision & Mission</h1>
            <h3>"Enrich every value of your time. & Provide and IT system with secure, swift and simple application for your daily activities"</h3>
        </div>
        <div class="center-holder">
            <a href="#" class="button-primary">Read More</a>
        </div>		
    </div>
</div>-->
<!-- Parallax Section END -->

<!-- Partners Section START -->
<div class="partner-section">
    <div class="container">	
        <div class="owl-carousel owl-theme partners" id="our-partners">
            <div class="item">
                <img src="<?php echo base_url(); ?>/assets/home/img/client/client-1.png" alt="partner-image">      
            </div>	

            <div class="item">
                <img src="<?php echo base_url(); ?>/assets/home/img/client/client-2.png" alt="partner-image">      
            </div>

            <div class="item">
                <img src="<?php echo base_url(); ?>/assets/home/img/client/client-3.png" alt="partner-image">      
            </div>

            <div class="item">
                <img src="<?php echo base_url(); ?>/assets/home/img/client/client-4.png" alt="partner-image">      
            </div>

            <div class="item">
                <img src="<?php echo base_url(); ?>/assets/home/img/client/client-5.png" alt="partner-image">      
            </div>

            <div class="item">
                <img src="<?php echo base_url(); ?>/assets/home/img/client/client-6.png" alt="partner-image">      
            </div>

            <div class="item">
                <img src="<?php echo base_url(); ?>/assets/home/img/client/client-7.png" alt="partner-image">      
            </div>

            <div class="item">
                <img src="<?php echo base_url(); ?>/assets/home/img/client/client-8.png" alt="partner-image">      
            </div>
        </div>  		     	
    </div>
</div>
<!-- Partners Section END -->