<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dosen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->helper(array('form', 'url'));
        $this->load->helper('file');
    }


    public function index()
    {
        $data['title'] = 'My File';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $userEmail = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $id = $userEmail['id'];
        $this->load->model('model', 'm');
        $data['file'] = $this->m->getFileById($id);
        $this->form_validation->set_rules('filename', 'Filename', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('file/index', $data);
            $this->load->view('templates/footer');
        } else {
            $post = $this->input->post();
            $namafile = $_FILES['userfile']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $this->m->uploadfile($namafile);

            $this->user_id = $id;
            $this->file_name = $this->input->post('filename');
            $this->file = $namafile;
            $this->category = $this->input->post('category');
            $this->status = 'Pending';
            $this->date_created = time();
            $this->db->insert('file', $this);
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert"> New file added!       
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ');
            redirect('dosen');
        }
    }


    public function deletefile($id)
    {
        $this->load->model('model', 'm');
        $this->m->deletefile($id);
        $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">File Deleted!       
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ');
        redirect('dosen');
    }

    public function viewPdf($file)
    {
        $filepath = './assets/file/' . $file;
        if (!file_exists($filepath)) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert"> File is not exist!       
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('dosen');
        }
        if (!is_readable($filepath)) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert"> File format is not pdf!      
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('dosen');
        }

        header('Content-Lenght: ' . filesize($filepath));
        header("Content-Type: application/pdf");
        header('Content-Disposition: inline; filename="' . $file . '"');
        readfile($filepath);

        exit;
    }
}
