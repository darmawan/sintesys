<script src="<?php echo base_url('assets/admin/vendors/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/vendors/bower_components/Waves/dist/waves.min.js');?>"></script>
<script src="<?php echo base_url('assets/admin/js/validator.min.js');?>" type="text/javascript"></script>
<!--[if IE 9 ]> <script
src="vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
<![endif]-->
<script src="<?php echo base_url('assets/admin/js/functions.js');?>"></script>
<script>
$(function(){
  $("#xfrm").validator().on("submit",function(c)
  {
    if(c.isDefaultPrevented()){}else{
      var b="login/doLogin";
      var a=$("#xfrm").serialize();
      $.ajax({
        url:b,type:"POST",
        data:a,
        dataType:"html",
        beforeSend:function(){},
        success:function(d){
          if(d.substring(0,4)=="http")
          {
            console.log(d);
            window.location.href=d
          }else{
            $(".alert").html(d);
            $(".alert").slideDown()
          }
          setTimeout(function(){},2000)
        },
          error:function(){}});
          return false}
          return false
        }
      )
    });
  </script>
</body>
</html>
