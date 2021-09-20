<?php 

	$data['js'] = $js;
	$data['css'] = $css;

	$this->load->view('kuwu/included/header',$data);
	$this->load->view('kuwu/included/sidebar');
	$this->load->view($target);
	$this->load->view('kuwu/included/footer',$data);

 ?>