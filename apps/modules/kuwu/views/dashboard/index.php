<section id="main">
    <section id="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 animated slideInLeft">
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>./System Checking Mode <small>development mode</small></h2>
                        </div>
                        <div class="card-body card-padding p-b-10" id="cfakultas">
                            <p><?php echo  BASEPATH .'  <br>  '.APPPATH.' <br>'.VIEWPATH;?></p>
                            <br><br><br>
                            <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CMSKuring Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
                            <pre>
                                <?php
                                // echo $this->libglobal->kueriProject();
                                ?>
                            </pre>
                        </div>
                    </div>                    
                </div>                                
            </div>
        </div>
    </section>
</section>
