<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class v1 extends REST_Controller {

	function __construct() {
        parent::__construct();
    }

    public function cria_sessao($user_name, $senha) {
        $this->load->model('user_model');
        $id_user = $this->user_model->retorna_id_user($user_name);
        $password = $this->user_model->retorna_password($user_name); 

        if ($id_user != '' && $password!='') {
            $newdata = array(
                'username'  => $user_name,
                'id_user'   => $id_user,
                'logged_in' => TRUE
            );

            $this->session->set_userdata($newdata);

            return true;
        } else {
            return false;
        }
    }

    public function fecha_sessao() {
        unset(
            $_SESSION['username'],
            $_SESSION['id_user'],
            $_SESSION['logged_in']
        );

        $this->session->sess_destroy();
    }

    public function users_get() {
        $var = $this->get();
        $this->load->model('user_model');

        if ($var['nome']!='' && $var['user']!='' && $var['senha']!='') {
            $res = $this->user_model->verifica_usuario($var['nome'], $var['user'], md5($var['senha']));

            if ($res == 1) {
                $this->response("Usuário existente no banco de dados.", 200);
            } elseif ($res == 100) {
                $this->response("Houve um problema interno", 500);
            } else if ($res == 1000) {
                $this->response("Este usuário não está cadastrado no banco de dados.", 404);
            }

        } else {
            $this->response("Está faltando dados para a operação.", 400);
        }
    }

    public function users_post() {
        $var = $this->post();
        $this->load->model('user_model');

        if ($var['nome'] != '' && $var['user'] != '' && $var['senha'] != '') {
            $res = $this->user_model->adiciona_usuario($var['nome'], $var['user'], md5($var['senha']));

            if ($res == 1) {
                $this->response("Novo usuário foi cadastrado!", 200);
            } elseif ($res == 100) {
                $this->response("Este usuário já está cadastrado", 500);
            } else if ($res == 1000) {
                $this->response("Não foi possível cadastrar este usuário", 500);
            }

        } else {
            $this->response("Está faltando dados para a operação.", 400);
        }  
    }

    public function users_put() {
        $var = $this->put();

        $this->load->model('user_model');

        if ($var['nome'] != '' && $var['user'] != '' && $var['senha'] != '') {
            $res = $this->user_model->modifica_usuario($var['nome'], $var['user'], md5($var['senha']));

            if ($res == 1) {
                $this->response("Os dados do usuário foram alterados!", 200);
            } elseif ($res == 100) {
                $this->response("Os dados eram identicos.", 500);
            } else if ($res == 1000) {
                $this->response("Não foi possível modificar este usuário", 500);
            }

        } else {
            $this->response("Está faltando dados para a operação.", 400);
        }
    }

    public function users_delete() {
        $var = $this->input->get();

        $this->load->model('user_model');

        if ($var['nome'] != '' && $var['user'] != '' && $var['senha'] != '') {
            $res = $this->user_model->deleta_usuario($var['nome'], $var['user'], md5($var['senha']));

            if ($res == 1) {
                $this->response("Usuario deletado com sucesso!", 200);
            } elseif ($res == 100) {
                $this->response("Não foi possível deletar o usuario", 500);
            } else if ($res == 1000) {
                $this->response("Dados incompativeis. Não foi possivel deletar o usuario.", 500);
            }

        } else {
            $this->response("Está faltando dados para a operação.", 400);
        }
    }

    public function tags_get($tag='') {
        $var = $this->get();
        $sess = $this->cria_sessao($var['user'],md5($var['senha']));

        if ($sess) {
        	if ($tag == '') {	
    	    	$this->load->model('tag_model');

    	    	if ($this->tag_model->esta_vazia() != 0) {
    	    		$res = $this->tag_model->verifica_tag();
    	    		$this->response("Você tem algum(ns) TAGs cadastrada(s).", 200);
    	    	} else {
    	    		$this->response("Sem tags cadastradas", 200);
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
        } else {
            $this->response("Usuário inválido!", 403);
        }

        $this->fecha_sessao();
    }

    public function tags_post($tag='') {
        $var=$this->post();
        $sess = $this->cria_sessao($var['user'],md5($var['senha']));

        if ($sess) {
            if ($tag=='') {
                if (!empty($var['tag'])) {
                    $this->load->model('tag_model');

                    $res=$this->tag_model->adiciona_tag($var['tag']);

                    if ($res==1) {
                        $this->response("TAG cadastrada com sucesso!",200);
                    } elseif ($res==100) {
                        $this->response("Tag já está cadastrada",202);
                    }

                } else {
                    $this->response("Erro: é preciso de uma tag para processamento", 400);
                }
                
            } else {
                $this->load->model('mentions_model');
                $res = $this->mentions_model->mention_crawler($tag);

                if ($res==1) {
                    $this->response("Tweets capturadas com sucesso!",200);
                } elseif ($res==100) {
                    $this->response("Não foi encontrado novos tweets",202);
                } elseif ($res==1000) {
                    $this->response("ERRO",500);
                } else {
                    $this->response("ERRO",500);
                }

            }
        } else {
            $this->response("Usuário inválido!", 403);
        }

        $this->fecha_sessao();            
    }

    public function tags_put($tag='') {
        $var=$this->put();
        $sess = $this->cria_sessao($var['user'],md5($var['senha']));

        if ($sess) {
            if ($tag=='') {
                $this->response ("put tag ", 404);
            } else {
                $this->response ("put mention ", 404);
            }
        } else {
            $this->response("Usuário inválido!", 403);
        }

        $this->fecha_sessao();
    }

    public function tags_delete ($tag='') {
        $var=$this->input->get();
        $sess = $this->cria_sessao($var['user'],md5($var['senha']));

        if ($sess) {
            if ($tag=='') {
                $this->load->model('tag_model');

                $res=$this->tag_model->deleta_tag($var['tag']);

                if ($res==1) {
                    $this->response("OK: TAG deletada com sucesso!", 200);
                } elseif ($res==100) {
                    $this->response("ERRO: Usuário não possui essa tag cadastrada", 404);
                } elseif ($res==1000) {   
                    $this->response("ERRO: TAG não encontrada.", 404);
                } else {
                    $this->response("ERRO", 500);
                }
            } else {
                $this->load->model('mentions_model');
                $res=$this->mentions_model->deleta_mentions($tag);

                if ($res==1) {
                    $this->response("Tweets foram deletados com sucesso", 200);
                } elseif ($res==100) {
                    $this->response("Não foram encontrados tweets para serem deletados", 404);
                } else {
                    $this->response("ERRO", 500);
                }
            }
        } else {
            $this->response("Usuário inválido!", 403);
        }

        $this->fecha_sessao();
    }

	public function index_get() {
		$this->response("get",200);
	}

	public function index_post() {
		$this->response("post",200);
	}

    public function index_put() {
        $this->response("put",200);
    }

    public function index_delete() {
        $this->response("delete",200);
    }
}
