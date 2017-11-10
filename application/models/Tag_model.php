<?php
class tag_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_User_ID($user_name) {
        $var = $this->db->query("SELECT id FROM usuarios WHERE user='$user_name'")->result_array();

        if (sizeof($var) != 0) {
            $var = $var[0]['id'];    
        } else {
            return 0;
        }    

        return $var;
    }

    public function esta_vazia ($user_name) {
        $id_user = $this->get_User_ID ($user_name);
        $query = $this->db->query("SELECT * FROM search_tags WHERE ID_User='$id_user' LIMIT 1");
        
        return $query->num_rows();
    }

    public function adicionaTag($user='', $tagBD='') {
        $flag=true;

        $id=$this->get_User_ID($user);

        if ($id == 0) {
            return 1000;
        }

        $tags=$this->getTag($user);

        if ($tagBD[0] != "#") {
            $tagBD = '#'.substr($tagBD, 0, strlen($tagBD));
        }

        foreach ($tags as $key => $value) {
            if ($value['term']==$tagBD) {
                $flag=false;

                return 100;
            }
        }
        
        if ($flag==true) {
            $this->db->query("INSERT INTO search_tags (term,ID_User) VALUES ('$tagBD','$id')");
        }

        return 1;
    }

    public function getTag ($user) {
        $id=$this->get_User_ID($user);
        $query=$this->db->query("SELECT term FROM search_tags WHERE ID_User='$id'")->result_array();

        return $query;
    }

    public function deleteTag ($users='',$tags='') {
        $user_id=$this->get_User_ID($users);

        if ($tags[0] != "#") {
            $tags = '#'.substr($tags, 0, strlen($tags));
        }

        $res = $this->db->query("SELECT id FROM search_tags WHERE term='$tags'")->result_array();
        
        if (empty($res)==true) {
            $res=null;
        } else {
            $res=$res[0]['id'];
            $res_user_id=$this->db->query("SELECT ID_User FROM search_tags WHERE id='$res'")->result_array();
            $res_user_id=$res_user_id[0]['ID_User'];
        }

        if ($res != null) { 
            if($user_id == $res_user_id) {
                //$id = $res[0]['id'];
                //$res1 = $this->db->query("DELETE FROM search_tags WHERE ID='$id'");
                $res1 = $this->db->query("DELETE FROM search_tags WHERE ID='$res'");
                return 0;

            } else {
                return 1;
            }
        } else {
            return 2;
        }

        return 2;
    }
}