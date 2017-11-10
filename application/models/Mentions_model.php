<?php
class Mentions_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function esta_vazia ($user_name='', $tag='') {
        $id_user = $this->get_User_ID ($user_name);
        $tag_id = $this->get_Tag_ID($tag);

        $query = $this->db->query("SELECT * FROM tweets WHERE user_id='$id_user' AND tag_id='$tag_id' LIMIT 1");

        return $query->num_rows();
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

    public function get_Tag_ID($tag_name='') {
        if ($tag_name[0] != "#") {
            $tag_name = '#'.substr($tag_name, 0, strlen($tag_name));
        }

        $var=$this->db->query("SELECT id FROM search_tags WHERE term='$tag_name'")->result_array();

        if (sizeof($var) != 0) {
            $var = $var[0]['id'];    
        } else {
            return 0;
        }  

        return $var;
    }

    public function listagem_mentions($user_name='', $tag='') {
        $tag_id = $this->get_Tag_ID($tag);
        $user_id = $this->get_User_ID($user_name);
        $query = $this->db->query("SELECT * FROM tweets WHERE tag_id='$tag_id' AND user_id='$user_id'")->result_array();

        return $query;
    }

    public function deletaMention($user_name='',$tag='') {
        $tag_id = $this->get_Tag_ID($tag);
        $user_id = $this->get_User_ID($user_name);

        $res = $this->db->query("DELETE FROM tweets WHERE tag_id='$tag_id' AND user_id='$user_id'");

        var_dump($res);
        die;

        return $res;
    }
}