<!-- Page Title Section START -->
<?php
if ($content) {
    $div = '<div class="page-title-section" style="background-image: url(#1);">'
        . '<div class="container"><div class="page-title center-holder">'
        . '<h1>#2</h1><ul>'
        . '<li><a href="#3">Home</a></li>'
        . '<li><a href="javascript:;">#4</a></li></ul></div></div></div>';
    echo generete_page_title_section(array(base_url('assets/home/img/bg/bg-4.jpg'), $kategori, base_url(), $content->article_title), $div);
?>
    <!-- Page Title Section END -->

    <!-- Blog Post START -->
    <div class="container icon-" style="margin-top: 100px; margin-bottom: 100px;">
        <div class="row">
            <?php
            $p = array(1, 2, 3, 4, 10, 11);

            $kelas = (in_array($content->type_id, $p)) ? '' : 'col-md-8 col-sm-8 col-xs-12'
            ?>
            <!-- Left Side START -->
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="blog-post">
                    <?php if ($img_konten) { ?>
                        <img src="<?php echo base_url('publik/rabmag/image/' . $img_konten->image_path); ?>" alt="<?php echo base_url('publik/rabmag/image/' . $img_konten->image_name); ?>">
                    <?php }; ?>

                    <h4><?php echo $content->article_title; ?></h4>

                    <div class="blog-post-info">
                        <i class="icon-users"></i><span>Admin</span>
                    </div>
                    <div class="blog-post-info">
                        <i class="icon-calendar-6"></i><span><?php echo date('d M Y', strtotime($content->publish_date)); ?></span>
                    </div>
                    <div class="blog-post-info">
                        <i class="icon-attachment"></i><span><?php echo $kategori; ?></span>
                    </div>
                    <!--<p class="mt-25"></p>-->
                    <?php
                    $k = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content->content);
                    echo str_replace("<p>", '<p class="mt-25">', $k);
                    ?>
                    <!--<p class="mt-25">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. </p>-->

                    <div class="blog-post-share">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 left-holder">
                                <a href="javascript:;"><?php echo $kategori; ?>,</a>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 share-icons right-holder">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Left Side END -->

            <!-- Right Side START -->
            <div class="col-md-4 col-sm-4 col-xs-12">

                <div class="blog-post-left">
                    <h4>Recent Posts</h4>
                    <div class="recent-posts">
                        <div class="row">
                            <div class="col-md-5 col-sm-5 col-xs-12 recent-posts-img">
                                <img src="http://via.placeholder.com/116x61" alt="blog-image">
                            </div>

                            <div class="col-md-7 col-sm-7 col-xs-12 recent-posts-text">
                                <p><a href="#">There are many variations of passages of aaaaaa bbbb</a></p>
                                <span>New 30, 2017</span>
                            </div>
                        </div>
                    </div>

                    <div class="recent-posts">
                        <div class="row">
                            <div class="col-md-5 col-sm-5 col-xs-12 recent-posts-img">
                                <img src="http://via.placeholder.com/116x61" alt="blog-image">
                            </div>

                            <div class="col-md-7 col-sm-7 col-xs-12 recent-posts-text">
                                <p><a href="#">There are many variations of passages of</a></p>
                                <span>New 30, 2017</span>
                            </div>
                        </div>
                    </div>

                    <div class="recent-posts">
                        <div class="row">
                            <div class="col-md-5 col-sm-5 col-xs-12 recent-posts-img">
                                <img src="http://via.placeholder.com/116x61" alt="blog-image">
                            </div>

                            <div class="col-md-7 col-sm-7 col-xs-12 recent-posts-text">
                                <p><a href="#">There are many variations of passages of</a></p>
                                <span>New 30, 2017</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="blog-post-left categories">
                    <h4>Product & Service</h4>
                    <ul>
                        <?php
                        $k = '';
                        $r = $this->Data_model->ambilDataWhere(DB_ARTICLE, array("tags <>" => '', 'is_published' => 1), 'tags', 'ASC', 'tags', $select = 'tags, count(tags) as jml', '');
                        //                    echo $this->db->last_query();
                        foreach ($r as $b) :
                            $link = '';
                            $k .= '<li><a href="#">' . $b->tags . ' <span>(' . $b->jml . ')</span> </a></li>';
                        endforeach;
                        echo $k;
                        ?>
                    </ul>
                </div>

                <!--            <div class="blog-post-left about">
                            <h4>About Us</h4>
                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. </p>
                        </div>-->
            </div>
            <!-- Right Side END -->
        </div>
    </div>
    <!-- Blog Post END -->
<?php } else { ?>
    <div class="container icon-" style="margin-top: 100px; margin-bottom: 100px;">
        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <!-- <div class="blog-post"> -->
                <div class="blog-post">
                    <h4>Konten dengan bahasa terpilih tidak tersedia.</h4>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
<?php }; ?>