<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class v1 extends REST_Controller {

	function __construct() {
        parent::__construct();
    }

    public function cria_sessao($user_name) {
        $this->load->model('user_model');
        $id_user = $this->user_model->retornaIDUser($user_name);

        if (sizeof($id_user) != 0) {
            $id_user = $id_user[0]['id'];    
        } else {
            return 0;
        }  

        $newdata = array(
            'username'  => $user_name,
            'id_user'   => $id_user,
            'logged_in' => TRUE
        );

        $this->session->set_userdata($newdata);
    }

    public function tags_get($tag='') {
        $var=$this->get();
        $this->cria_sessao($var['user']);

    	if ($tag == '') {	
	    	$this->load->model('tag_model');

	    	if ($this->tag_model->esta_vazia($var['user']) != 0) {
	    		$res = $this->tag_model->getTag($var['user']);
	    		$this->response("Você tem algum(ns) TAGs cadastrada(s).", 200);
	    	} else {
	    		$this->response("Sem tags cadastradas", 500);
	    	}
    	} else {
    		$this->load->model('mentions_model');

            if ($this->mentions_model->esta_vazia($var['user'],$tag) != 0) {
                $res = $this->mentions_model->listagem_mentions($var['users'],$tag);
                $this->response("Mentions encontradas", 200);
            } else {
                $this->response("Não há menções para mostrar...", 500);
            }
    	}
    }

    public function tags_post($tag='') {
        if ($tag=='') {
            $var=$this->post();

            if (!empty($var['user']) && !empty($var['tag'])) {
                $this->load->model('tag_model');

                $res=$this->tag_model->adicionaTag($var['user'],$var['tag']);

                if ($res==1) {
                    $this->response("OK",200);
                } elseif ($res==100) {
                    $this->response("Erro: Tag já cadastrada",500);
                } elseif ($res == 1000) {
                    $this->response("Erro: Usuario não encontrado",500);
                }

            } else {
                $this->response("Erro: há uma falta em USER e-ou TAG", 500);
            }
            
        } else {
            $this->load->library('twitterlib');
            $this->twitterlib->searchone();
        }
    }

    public function tags_put($tag='') {
        $var=$this->put();

        if ($tag!='') {

        } else {

        }
    }

    public function tags_delete ($tag='') {
        $var=$this->input->get();
        $this->cria_sessao($var['user']);

        if ($tag=='') {
            $this->load->model('tag_model');

            $res=$this->tag_model->deleteTag($var['user'],$var['tag']);

            if ($res==0) {
                $this->response("OK",200);
            } elseif ($res==1) {
                $this->response("Não encontrado",404);
            } else {   
                $this->response("Erro",500);
            }
        } else {
            $this->load->model('mentions_model');

            $res=$this->mentions_model->deletaMention($var['user'],$var['tag']);
        }
    }

	public function index_get() {
		$arr = array('nomes' => array('Eduardo', 'Joao', "Pedro"));
		$this->response($arr,200);
	}

	public function index_post() {
		$this->response("post",500);
	}

    public function index_put() {
        $this->response("put",500);
    }

    public function index_delete() {
        $this->response("delete",500);
    }
}
