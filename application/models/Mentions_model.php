<?php
class Mentions_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function esta_vazia ($tag='') {
        $id_user = $this->session->userdata('id_user');
        $tag_id = $this->get_Tag_ID($tag);

        $query = $this->db->query("SELECT * FROM tweets WHERE user_id='$id_user' AND tag_id='$tag_id' LIMIT 1");

        return $query->num_rows();
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

    public function mention_crawler ($tag='') {
        if ($tag[0] != "#") {
            $tag = '#'.substr($tag, 0, strlen($tag));
        }

        $this->load->library('twitterlib');
        $res = $this->twitterlib->searchone($tag);

        return $res;
    }

    public function listagem_mentions($tag='') {
        $tag_id = $this->get_Tag_ID($tag);
        $id_user = $this->session->userdata('id_user');
        
        $query = $this->db->query("SELECT * FROM tweets WHERE tag_id='$tag_id' AND user_id='$id_user'")->result_array();

        return $query;
    }

    public function deleta_mentions($tag='') {
        $tag_id = $this->get_Tag_ID($tag);
        $id_user = $this->session->userdata('id_user');

        $var = $this->listagem_mentions($tag);

        if (sizeof($var)!=0) {
            $res = $this->db->query("DELETE FROM tweets WHERE tag_id='$tag_id' AND user_id='$id_user'");
        } else {
            return 100;
        }

        return 1;
    }
}