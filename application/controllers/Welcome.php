<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Welcome extends REST_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct() {
        parent::__construct();
    }

    public function teste_get() {
        echo "teste";
    }

    public function validacao_get() {
        echo "validacao";
    }

    public function tags_get($id='') {
        $var=$this->get();
    	$this->load->model('tag_model');

    	if ($this->tag_model->esta_vazia() != 0) {
    		$res = $this->tag_model->getTag($var['users']);
    		$this->response($res, 200);
    	} else {
    		$this->response("Sem tags cadastradas", 500);
    	}
    }

    public function tags_post($id='',$tag='') {
    	$var=$this->post();
    	$this->load->model('tag_model');

    	$res=$this->tag_model->adicionaTag($var['users'],$var['tags']);

    	if ($res==true) {
    		$this->response("OK",200);
    	} else {
    		$this->response("Erro",500);
    	}
    }

    public function tags_put() {

    }

    public function tags_delete ($id='', $tag='') {
        $var=$this->input->get();
    	$this->load->model('tag_model');

    	$res=$this->tag_model->deleteTag($var['users'],$var['tags']);

    	if ($res==0) {
    		$this->response("OK",200);
        } elseif ($res==1) {
            $this->response("Não encontrado",404);
    	} else {   
    		$this->response("Erro",500);
    	}
    }

	public function index_get() {
		$arr = array('nomes' => array('Eduardo', 'Joao', "Pedro"));
		$this->response($arr,200);
	}

	public function index_post() {
		$this->response("post",500);
	}
}
