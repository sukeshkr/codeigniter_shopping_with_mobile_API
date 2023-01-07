<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Options extends MY_Auth_Controller {

	protected $ci_name;//declare ci_name varriabe current controler name as image folder name to upload image

    public function __construct() 
    {
	    parent::__construct();
	    $this->ci_name = strtolower($this->router->fetch_class());
	    $this->load->model('Option_model','model');
        if (!$this->is_logged_in()) //login only registered user from db
        { 
          redirect('Login');
        }
	}
  
    public function index() {

    	$data['list'] = $this->model->getOptions();
    	$this->load->view('option/list',$data);
    }

    public function create() {

        $this->form_validation->set_rules('name', 'User Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description','Description', 'trim|required|xss_clean');
        $this->form_validation->set_rules('variants_name[]','Variants', 'trim|required|xss_clean');
        $this->form_validation->set_rules('cat_name[]','category name', 'trim|required|xss_clean');

        if($this->form_validation->run() == FALSE) 
        {
            $data['catList'] = $this->model->getCategory();

            $this->load->view('option/add',$data);

        }
        else {

            $name = $this->input->post('name');
            $variant_check = $this->input->post('variant_check');
            $description = $this->input->post('description');
            $variants_name = $this->input->post('variants_name'); 

            $cat_name = $this->input->post('cat_name');

            $value=array('name'=>$name,'go_to_variant'=>$variant_check,'description'=>$description);

            $this->model->insertOptions($name,$value,$variants_name,$cat_name);

            $this->session->set_flashdata('add', 'Added Successfully');

            redirect('Options');
        }

    }

    public function edit()
    {
        $id = $this->uri->segment(3);
        $data['result'] = $this->model->getOptions($id);
        $data['optionList'] = $this->model->getOptionsList($id);
        $data['catList'] = $this->model->getOptionsCat($id);
        $data['catAllList'] = $this->model->getCategory();
        $this->load->view('option/edit',$data);
    }

    public function update() {

        $id = $this->input->post('id');

        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description','Description', 'trim|required|xss_clean');

        if($this->form_validation->run() == FALSE) {

            $data['result'] = $this->model->getOptions($id);
            $data['optionList'] = $this->model->getOptionsList($id);
            $data['catList'] = $this->model->getOptionsCat($id);
            $data['catAllList'] = $this->model->getCategory();
            $this->load->view('option/edit',$data);

        }
        else {

            $name = $this->input->post('name');

            $type = $this->input->post('type');
            $variant_check = $this->input->post('variant_check');
            $description = $this->input->post('description');

            $variants_name = $this->input->post('variants_name');

            $cat_name = $this->input->post('cat_name');

            $value=array('name'=>$name,'type'=>$type,'go_to_variant'=>$variant_check,'description'=>$description);

            $this->model->updateOptions($id,$value,$name,$variants_name,$cat_name);

            $this->session->set_flashdata('update', 'Added Successfully');
            redirect('Options');
        }
    }

    public function deleteOptcatList(){
       
        $id=$_POST['rowid'];
        $this->model->deleteOptioncatList($id);
    }

    public function deleteOptList(){
       
        $id=$_POST['rowid'];
        $this->model->deleteOptionList($id);
    }

    public function delete(){

        $this->load->view('option/delete');
        
        if (isset($_POST['delete'])) 
        {
            $id=$_POST['rowid'];
            $this->model->deleteOptions($id,$name);
            redirect('Options');
        }
    }

}
