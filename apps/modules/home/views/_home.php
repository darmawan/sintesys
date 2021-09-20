<!-- tempat menampung konten -->

<!-- Slider START -->
<div class="pogoSlider" id="js-main-slider">
    <?php
    $this->load->view(TEMPLATE . 'slider_konten');
    ?>	
</div>
<!-- Slider END -->

<!-- Top Articles START -->
<div class="section-block">
    <div class="container">
        <div class="section-heading center-holder">
            <?php
            $topart = '';
            foreach (generate_modul('Home', 'artikel', array('tags' => 'slogan glosika')) as $baris):
                $topart = '<h2>' . $baris->article_title . '</h2>' . $baris->content;
            endforeach;
            echo $topart;
            ?>
        </div>	
        <div class="row">
            <?php
            $kontentopart = '';
            $x = 0;
            $y = 0;
            foreach (generate_modul('glosika', 'layanan', array('inpo'=>'induk')) as $baris):
                $link = base_url('e/' . bin2hex($baris->product_id) . '/' . generate_title_to_url($baris->product_title), false);
                if (($x % 2) == 0) {
                    $kontentopart .= ($y > 0) ? '</div>' : '';
                    $kontentopart .= '<div class="col-md-6 col-sm-6 col-xs-6">';
                } else {
                    
                }
                $kontentopart .= '<article class="service-article clearfix">
                    <div class="article-icon">
                        <i class="' . $baris->page_title . '"></i>
                    </div>
                    <div class="article-text">
                        <h3>' . $baris->product_title . '</h3>
                        <p>' . $baris->summary . '</p>
                        <a href="http' . $link . '">Read More</a>
                    </div>					
                </article>';
                $x++;
                $y++;
            endforeach;
            $kontentopart .= '</div>';
            echo $kontentopart;
            ?>
        </div>
    </div>
</div>
<!-- Top Articles END -->

<!-- Services START -->
<div class="section-block-grey">
    <div class="container">
        <div class="section-heading center-holder">
            <h2>Our Product & Services</h2>
            <?php echo constant('NM_DESKRIPSI_PT'); ?>
        </div>	
        <div class="row">
            <?php
//            $div = '<div class="col-md-4 col-sm-4 col-xs-12">
//                <div class="service-grid">
//                    <img src="#4" alt="service-image">
//                    <div class="inner-padd">
//                        <h4>#4</h4><p>#5</p>
//                        <a href="#2">Read more</a>
//                    </div>
//                </div>
//            </div>';
//            echo genereate_modul_artikel('glosika', 'layanan', '', $div,'','e');

            $kontenservice = '';
            foreach (generate_modul('Our Product & Services', 'artikel') as $ba):
            $link = base_url() . 'a/' . bin2hex($ba->article_id) . '/' . generate_title_to_url(trim($ba->article_title));
            $img_konten = $this->Data_model->satuData(DB_IMAGE, array('reffid' => $ba->article_id));
            if ($img_konten) {
            $image = ($img_konten->image_path == '') ? 'assets/home/img/glosika.jpg' : 'publik/rabmag/image/' . $img_konten->image_path;
            } else {
            $image = 'assets/home/img/glosika.jpg';
            }

            $kontenservice .= '<div class="col-md-4 col-sm-4 col-xs-12">
                <div class="service-grid">
                    <img src="' . $image . '" alt="service-image">
                    <div class="inner-padd">
                        <h4>' . $ba->article_title . '</h4><p>' . $ba->summary . '</p>
                        <a href="' . trim($link) . '">Read more</a>
                    </div>
                </div>
            </div>';
            endforeach;
            echo $kontenservice;
            ?>

        </div>
    </div>
</div>
<!-- Services END -->

<!-- CountUp Section START -->
<div class="section-block-parallax" style="background-image: url(assets/home/img/bg/bg-5.jpg);">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="countup-box">
                    <h4 class="countup">1200</h4>
                    <h5>Hours Of Work</h5>
                </div>				
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="countup-box">
                    <h4 class="countup">120</h4>
                    <h5>Complete Project</h5>
                </div>				
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="countup-box">
                    <h4 class="countup">100</h4>
                    <h5>Happy Clients</h5>
                </div>				
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="countup-box">
                    <h4 class="countup">20</h4>
                    <h5>Awards</h5>
                </div>				
            </div>
        </div>
    </div>
</div>
<!-- CountUp Section END -->

<!-- Portfolio START -->
<div class="section-block-grey">
    <div class="container">
        <div class="section-heading center-holder">
            <h2>Latest Projects</h2>
        </div>	

        <div class="latest-projects">
            <div id="filters" class="center-holder" style="margin-bottom: 20px;">
                <button class="isotop-button" data-filter="*">Show all</button>
                <?php
                $div = '<button class="isotop-button" data-filter=".#1">#2</button>';
                echo generate_kategori_project(6, array('project_id <>' => ''), 'type_belongto', 'ASC', $div);
                ?>
            </div>
            <div class="row">
                <div class="isotope-grid">
                    <?php
                    $div = '<div class="col-md-4 col-sm-6 col-xs-12 isotope-item #1"><a href="#2"><div class="project-item"><div class="overlay-container"><img src="#3" alt="#1"><div class="project-item-overlay"><h4>#4</h4><p>#5</p></div></div></div></a></div>';
                    echo genereate_recent_project(6, array('project_id <>' => ''), 'publish_date', 'DESC', $div, 'ada');
                    ?>
                </div>
            </div>            
        </div> 	
    </div>		
</div>
<!-- Portfolio END -->

<!-- Testmonials START -->
<div class="section-block-parallax" style="background-image: url(assets/home/img/bg/bg-4.jpg);">
    <div class="container">
        <div class="section-heading white-color center-holder">
            <h2></h2>			
        </div>	
    </div>
</div>
<!-- Testmonials END -->

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
<!-- Partners Section END