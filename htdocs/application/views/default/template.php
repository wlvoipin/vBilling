<?php 
	//load main header
    $this->load->view('default/header'); 
    
    //load main menu
	if($main_menu != '')
    {
        $this->load->view($main_menu);
    }
    
    //load sub menu if available
    if($sub_menu != ''){
		$this->load->view($sub_menu);
	}
    
    //load main content
	$this->load->view($main_content);
    
    //load footer
	$this->load->view('default/footer');
?>