<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
require APPPATH . '/libraries/BaseController.php';
class Category extends BaseController{
    function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();   
        $this->load->model('Category_model');
    } 

    /*
     * Listing of category
     */
    function index()
    {
        $data['category'] = $this->Category_model->get_all_category();
        foreach($data['category'] as $v){
            $this->load->model('Technology_model');
            $data['technology'] = $this->Technology_model->get_technology($v['tech_id']);
        }
        $data['_view'] = 'category/index';
        $this->global['pageTitle'] = 'eVideoPlus : Category';
        $this->loadViews("category/index", $this->global, $data, NULL);
    }

    /*
     * Adding a new category
     */
    function add()
    {   
        $this->load->library('form_validation');

		$this->form_validation->set_rules('name','Name','max_length[3000]|required');
		
		if($this->form_validation->run())     
        {   $today = date("m/d/Y h:i:s a");
            $params = array(
				'tech_id' => $this->input->post('tech_id'),
				'name' => $this->input->post('name'),
				'addedBy' => $this->global['name'],
				'date' => $today,

            );
            
            $category_id = $this->Category_model->add_category($params);

            redirect('category/index');
        }
        else
        {
			$this->load->model('Technology_model');
			$data['all_technology'] = $this->Technology_model->get_all_technology();
            
            $data['_view'] = 'category/add';
            $this->global['pageTitle'] = 'eVideoPlus : Add Videos';
            $this->loadViews("category/add", $this->global, $data, NULL);
        }
    }  

    /*
     * Editing a category
     */
    function edit($cat_id)
    {   
        // check if the category exists before trying to edit it
        $data['category'] = $this->Category_model->get_category($cat_id);
        
        if(isset($data['category']['cat_id']))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('name','Name','max_length[3000]|required');
		
			if($this->form_validation->run())     
            {   $today = date("m/d/Y h:i:s a");
                $params = array(
					'tech_id' => $this->input->post('tech_id'),
					'name' => $this->input->post('name'),
                    'addedBy' => $this->global['name'],
                    'date' => $today,
                );

                $this->Category_model->update_category($cat_id,$params);            
                redirect('category/index');
            }
            else
            {
				$this->load->model('Technology_model');
				$data['all_technology'] = $this->Technology_model->get_all_technology();

                $data['_view'] = 'category/edit';
                $this->global['pageTitle'] = 'eVideoPlus : Edit Videos';
                $this->loadViews("category/edit", $this->global, $data, NULL);
            }
        }
        else
            show_error('The category you are trying to edit does not exist.');
    } 

    /*
     * Deleting category
     */
    function remove($cat_id)
    {
        $category = $this->Category_model->get_category($cat_id);

        // check if the category exists before trying to delete it
        if(isset($category['cat_id']))
        {
            $this->Category_model->delete_category($cat_id);
            redirect('category/index');
        }
        else
            show_error('The category you are trying to delete does not exist.');
    }
    
}
