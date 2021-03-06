<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Artikel extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('curl');
		$this->load->model('mdl_artikel');
    }

    function index(){
        $artikel = $this->db->get('artikel');
        $this->load->view('view', compact('artikel'));
    }

    function form_insert(){
        $this->load->view('form_artikel');
    }
  
    function add_artikel(){
        $judul = $this->input->post('judul_artikel');
        $artikel = $this->input->post('isi_artikel');
        $data = array('text' => $artikel);
        $url = 'https://api.prosa.ai/v1/topics';
        $result = json_decode($this->curl->postCURL($url, $data), true);
        $inserted = array(
            'judul' => $judul, 
            'isi' => $artikel, 
            'topic' => $result['topic'], 
            'confidence' => $result['confidence']
        );
        $this->db->insert('artikel', $inserted); 
        redirect(base_url('artikel'));
    }

    function hapus($id){
        $where = array('id' => $id);
        $this->db->delete('artikel' ,$where);
        redirect(base_url('artikel'));
    }

    function delete($id){
      $this->Mdl_produk->delete_by_id($id);
      echo json_encode(array("status" => TRUE));
    }

    function form_edit($id){
        $where = array('id' => $id);
        $data['artikel'] = $this->mdl_artikel->edit_data($where, 'artikel')->row();
        $this->load->view('edit_artikel', $data);
    }

    function update(){
		$id = $this->input->post('id');
		$judul = $this->input->post('judul_artikel');
		$artikel = $this->input->post('isi_artikel');
		$data = array('text' => $artikel);
		$url = 'https://api.prosa.ai/v1/topics';
		$result = json_decode($this->curl->postCURL($url, $data), true);
		$updated = array(
			'judul' => $judul, 
			'isi' => $artikel, 
			'topic' => $result['topic'], 
			'confidence' => $result['confidence']
		);
		$where = array(
			'id' => $id
		);
		$this->mdl_artikel->update_data('artikel', $where, $updated);
		redirect(base_url('artikel'));
    }

    function selanjutnya() {
        $id=$this->uri->segment(3);
        $artikel['artikel']=$this->mdl_artikel->per_id($id);
        $this->load->view('view_detail',$artikel);
    }
}

?>