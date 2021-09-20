<!-- Page Title Section START -->
<div class="page-title-section" style="background-image: url(<?php echo base_url('assets/home/img/bg/bg-5.jpg'); ?>);">
    <div class="container">
        <div class="page-title center-holder">
            <h1>Contact</h1>
            <ul>
                <li><a href="<?php echo base_url();?>">Home</a></li>
                <li><a href="javascript:;">Contact</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Title Section END -->



<!-- Contact icos START -->
<div class="partner-section-grey">
    <div class="container" > 
        <div class="contact-box">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12 contact-icon">
                    <div class="icon-box">
                        <i class="icon-phone-reciever"></i>
                    </div>
                    <h4>Phone</h4>
                    <p> Phone: <?php echo constant('NM_TLP');?></p>
                    <p> Fax: <?php echo constant('NM_FAX');?></p>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12 contact-icon">
                    <div class="icon-box">
                        <i class="icon-map-location"></i>
                    </div>

                    <h4>Address</h4>
                    <p><?php echo constant('NM_ALAMAT');?></p>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12 contact-icon">
                    <div class="icon-box">
                        <i class="icon-chat-bubbles"></i>
                    </div>

                    <h4>Email</h4>
                    <p>Email: <?php echo constant('NM_EMAIL');?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact icos END -->



<!-- Contact Form Section Start -->
<div class="section-block">
    <div class="container">
        <div class="section-heading center-holder">
            <h2>Send us a message</h2>
        </div>
        <div class="row mt-70">
            <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">

                <form method="post" class="primary-form">
                    <div class="col-xs-12">
                        <input type="text" name="name" placeholder="Your Name">
                    </div>
                    <div class="col-xs-6">
                        <input type="email" name="email" placeholder="E-mail adress">
                    </div>
                    <div class="col-xs-6">
                        <input type="text" name="phone" placeholder="Phone Number">
                    </div>	
                    <div class="col-xs-12">
                        <textarea name="message" placeholder="Your Message"></textarea>
                    </div>	
                    <div class="center-holder">
                        <button type="submit" class="button button-primary mt-30">Send Message</button>
                    </div>							
                </form>	

            </div>
        </div>
    </div>
</div>
<!-- Contact Form Section End -->



<!-- Map Start -->
<div id="map">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAI5D77cb5DLEPmQOolZ2L5TcQ4LEo_QhY&callback=initMap">
    </script>  	
</div>
<!-- Map End -->