<?php
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function retorna_id_user ($user_name) {
        $res = $this->db->query("SELECT id FROM usuarios WHERE user='$user_name'")->result_array();

        if ($res != false){
            return $res[0]['id'];
        }

        return '';
    }

    public function retorna_password ($user_name) {
        $res = $this->db->query("SELECT password FROM usuarios WHERE user='$user_name'")->result_array();

        if ($res != false){
            return $res[0]['password'];
        }

        return '';
    }

    public function valida_dados($user, $senha) {
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

    public function verifica_usuario($nome, $user, $senha) {
        $var = $this->db->query("SELECT * FROM usuarios WHERE user='$user'")->result_array();

        if (sizeof($var) != 0) {
            $var = $var[0]['user'];    

            if ($var == $user) {
                return  1;
            } else {
                return 100;
            }

        }

        return 1000;
    }

    public function adiciona_usuario($nome, $user, $senha) {
        $var = $this->db->query("SELECT * FROM usuarios WHERE user='$user'")->result_array();

        if (sizeof($var) != 0) {
            $var = $var[0]['user'];    
        }

        if ($var != $user) {
            $this->db->query("INSERT INTO usuarios (user,password,name) VALUES ('$user','$senha','$nome')");

            return 1;
        } else {
            return 100;
        }

        return 1000;
    }

    public function modifica_usuario($nome, $user, $senha) {
        $var = $this->db->query("SELECT * FROM usuarios WHERE user='$user'")->result_array();

        if (sizeof($var) != 0 && $var[0]['user'] == $user) {
            if ($var[0]['name'] != $nome) {
                $this->db->query("UPDATE usuarios SET name='$nome' WHERE user='$user'");
            }

            if ($var[0]['password'] != $senha) {
                $this->db->query("UPDATE usuarios SET password='$senha' WHERE user='$user'");
            }

            if ($var[0]['name'] == $nome && $var[0]['password'] == $senha) {
                return 100;
            }

            return 1;
        } else {
            return 1000;
        }
    }

    public function deleta_usuario ($nome, $user, $senha) {
        $var = $this->db->query("SELECT * FROM usuarios WHERE user='$user'")->result_array();

        if (sizeof($var)!=0 && $var[0]['user']==$user && $var[0]['password']==$senha && $var[0]['name']==$nome) {

            $id=$this->retorna_id_user($user);
            $id=$id[0]['id'];
            $res=$this->db->query("DELETE FROM usuarios WHERE id='$id'");

            if ($res) {
                return 1;
            } else {
                return 100;
            }
            
        } else {
            return 1000;
        }
    }
}