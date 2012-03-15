<?php 
/*
 * Version: MPL 1.1
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * The Original Code is "vBilling - VoIP Billing and Routing Platform"
 * 
 * The Initial Developer of the Original Code is 
 * Digital Linx [<] info at digitallinx.com [>]
 * Portions created by Initial Developer (Digital Linx) are Copyright (C) 2011
 * Initial Developer (Digital Linx). All Rights Reserved.
 *
 * Contributor(s)
 * "Digital Linx - <vbilling at digitallinx.com>"
 *
 * vBilling - VoIP Billing and Routing Platform
 * version 0.1.3
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('settings_model');

		//validate login
		if (!user_login())
		{
			redirect ('home/');
		}
		else
		{
			if($this->session->userdata('user_type') == 'customer')
			{
				redirect ('customer/');
			}
            
            if($this->session->userdata('user_type') == 'reseller')
			{
				redirect ('reseller/');
			}
            
            if($this->session->userdata('user_type') == 'sub_admin')
			{
				redirect ('home/');
			}
		}
	}
    
    //Public function for image uploading	
	 public $data = array(
        'dir' => array(
            'original' => 'media/images/',
            'thumb' => 'media/images/thumbs/'
        ),
        'total' => 0,
        'images' => array(),
        'error' => ''
    );

	function index()
	{
		//$data['settings']		=	$this->settings_model->get_settings();
        
        $data['page_name']		=	'show_settings';
		$data['selected']       =   'settings';
        $data['sub_selected']	=	'show_settings';
        $data['page_title']		=	'SETTINGS';
		$data['main_menu']	    =	'default/main_menu/main_menu';
		$data['sub_menu']	    =	'default/sub_menu/settings_sub_menu';
		$data['main_content']	=	'settings/settings_view';
		$this->load->view('default/template',$data);
	}
    
    function update_settings()
    {
        $company_name = ucwords(strtolower($this->input->post('company_name')));
        $this->settings_model->update_settings('company_name', $company_name);
        $this->settings_model->update_settings('enable_rate_limits', $this->input->post('enable_rate_limits'));

        $full_img_name = '';
        if(isset($_FILES['userfile']['tmp_name']) && !empty($_FILES['userfile']['tmp_name']))
        {
            $c_upload['file_name']    	= md5(uniqid(000,999));
            $c_upload['upload_path']    = $this->data['dir']['original'];
            $c_upload['allowed_types']  = 'gif|jpg|png|jpeg|x-png';
            $c_upload['max_size']       = '1024';
            $c_upload['remove_spaces']  = TRUE;
            $c_upload['max_width']  = '500';
            $c_upload['max_height']  = '50';
    
            $this->load->library('upload', $c_upload);
            
            if ($this->upload->do_upload()) 
            {
                $img = $this->upload->data();
                
                // create thumbnail
                $new_image = $this->data['dir']['thumb'].'thumb_'.$img['file_name'];
                
                $c_img_lib = array(
                    'image_library'     => 'gd2',
                    'source_image'      => $img['full_path'],
                    'maintain_ratio'    => TRUE,
                    'width'             => 100,
                    'height'            => 100,
                    'new_image'         => $new_image
                );
                
                $this->load->library('image_lib', $c_img_lib);
                $this->image_lib->resize();
                
                $getting_file_extension = $_FILES['userfile']['name'];
                $info = pathinfo($getting_file_extension);
                
                //$full_img_name = $c_upload['file_name'].'.'.$info['extension'];
                $full_img_name = $img['file_name'];
                $this->settings_model->update_settings('logo', $full_img_name);
            } 
            else 
            {
                $this->data['error'] = $this->upload->display_errors();
                $this->session->set_flashdata('error',$this->data['error']);
                redirect('settings/');
            }
        }
        
        $this->session->set_flashdata('success','Changes Updated Successfully!');
        redirect('settings/');
    }
    
    function update_inv_settings()
    {
        $inv_footer = $this->input->post('inv_footer');
        $this->settings_model->update_settings('invoice_terms', $inv_footer);
        
        $same_logo = $this->input->post('same_logo');
        if($same_logo == '')
        {
            $same_logo = 0;
        }
        $this->settings_model->update_settings('company_logo_as_invoice_logo', $same_logo);
        
        $extra_cdr = $this->input->post('extra_cdr');
        $this->settings_model->update_settings_extra_cdr($extra_cdr);
        
        $full_img_name = '';
        if(isset($_FILES['userfile']['tmp_name']) && !empty($_FILES['userfile']['tmp_name']))
        {
            $c_upload['file_name']    	= md5(uniqid(000,999));
            $c_upload['upload_path']    = $this->data['dir']['original'];
            $c_upload['allowed_types']  = 'gif|jpg|png|jpeg|x-png';
            $c_upload['max_size']       = '1024';
            $c_upload['remove_spaces']  = TRUE;
            $c_upload['max_width']  = '500';
            $c_upload['max_height']  = '50';
    
            $this->load->library('upload', $c_upload);
            
            if ($this->upload->do_upload()) 
            {
                $img = $this->upload->data();
                
                // create thumbnail
                $new_image = $this->data['dir']['thumb'].'thumb_'.$img['file_name'];
                
                $c_img_lib = array(
                    'image_library'     => 'gd2',
                    'source_image'      => $img['full_path'],
                    'maintain_ratio'    => TRUE,
                    'width'             => 100,
                    'height'            => 100,
                    'new_image'         => $new_image
                );
                
                $this->load->library('image_lib', $c_img_lib);
                $this->image_lib->resize();
                
                $getting_file_extension = $_FILES['userfile']['name'];
                $info = pathinfo($getting_file_extension);
                
                //$full_img_name = $c_upload['file_name'].'.'.$info['extension'];
                $full_img_name = $img['file_name'];
                $this->settings_model->update_settings('invoice_logo', $full_img_name);
            } 
            else 
            {
                $this->data['error'] = $this->upload->display_errors();
                $this->session->set_flashdata('error_inv',$this->data['error']);
                redirect('settings/');
            }
        }
        
        $this->session->set_flashdata('success_inv','Changes Updated Successfully!');
        redirect('settings/');
    }
}