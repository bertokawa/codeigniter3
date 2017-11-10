<?php
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function retornaIDUser ($user_name) {
        $res = $this->db->query("SELECT id FROM usuarios WHERE user='$user_name'")->result_array();

        if ($res != false){
            return $res;
        }

        return '';
    }

    public function validaDado($user, $senha) {
    	$query = $this->db->query("SELECT password FROM usuarios WHERE user='$user'")->result_array();

        if (empty($query)) {
            return False;
        } else {
            $pass_db = $query[0]['password'];
        }

    	if($pass_db == $senha){
    		return True;
    	} else {
    		return False;
    	}
    }

    public function adicionaUser($user_post, $email_post, $senha_post) {
        $user = $this->session->userdata('user');
        $password = $this->session->userdata('password');
        $name = $this->session->userdata('name');
      
        $flag = true;

        
        if ($flag == true) {
            $this->db->query("INSERT INTO usuarios (user,password,name) VALUES ('$email_post','$senha_post','$user_post')");
        }
        
    }
}