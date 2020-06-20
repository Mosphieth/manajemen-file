<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kaprodi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'All File';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->model('model', 'm');
        $data['file'] = $this->m->getAllFileandUserName();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('file/allfile', $data);
        $this->load->view('templates/footer');
    }
    public function accept($id)
    {
        $this->load->model('model', 'm');
        $this->m->acceptFile($id);
        $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">File Accepted!       
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ');
        redirect('kaprodi');
    }

    public function refuse($id)
    {
        $this->load->model('model', 'm');
        $this->m->refuseFile($id);
        $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">File Refused!       
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ');
        redirect('kaprodi');
    }
}
