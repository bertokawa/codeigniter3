<?php
class tag_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function esta_vazia () {
        $id_user = $this->session->userdata('id_user');
        $query = $this->db->query("SELECT * FROM search_tags WHERE ID_User='$id_user' LIMIT 1");
        
        return $query->num_rows();
    }

    public function adiciona_tag($tag='') {
        $flag=true;
        $id_user = $this->session->userdata('id_user');
        $tags=$this->verifica_tag();

        if ($tag[0] != "#") {
            $tag = '#'.substr($tag, 0, strlen($tag));
        }

        foreach ($tags as $key => $value) {
            if ($value['term']==$tag) {
                $flag=false;

                return 100;
            }
        }
        
        if ($flag==true) {
            $this->db->query("INSERT INTO search_tags (term,ID_User) VALUES ('$tag','$id_user')");
        }

        return 1;
    }

    public function verifica_tag () {
        $id_user=$this->session->userdata("id_user");
        $query=$this->db->query("SELECT term FROM search_tags WHERE ID_User='$id_user'")->result_array();

        return $query;
    }

    public function deleta_tag ($tags='') {
        $id_user=$this->session->userdata("id_user");

        if ($tags[0] != "#") {
            $tags = '#'.substr($tags, 0, strlen($tags));
        }

        $res = $this->db->query("SELECT id FROM search_tags WHERE term='$tags'")->result_array();
        
        if (!empty($res)) {
            $res=$res[0]['id'];
            $res_user_id=$this->db->query("SELECT ID_User FROM search_tags WHERE id='$res'")->result_array();
            $res_user_id=$res_user_id[0]['ID_User'];

            if($id_user == $res_user_id) {
                $res1 = $this->db->query("DELETE FROM search_tags WHERE ID='$res'");
                return 1;
            } else {
                return 100;
            }
        }

        return 100;
    }
}