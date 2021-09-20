<?php 

	$data['js'] = $js;
	$data['css'] = $css;

	$this->load->view(VIEW_INCLUDE . '/header',$data);
	$this->load->view(VIEW_INCLUDE . '/sidebar');
	$this->load->view($target);
	$this->load->view(VIEW_INCLUDE . '/footer',$data);

 ?>