<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('user_model');
    }
    public function index()
    {
        // rule input form login
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        //cek rule jika false maka di kembalikan ke page login
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            //jika true maka di lanjutkan ke verifying user data
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        // jika user ada
        if ($user) {
            // jika user aktif
            if ($user['is_active'] == 1) {
                // cek password 
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];

                    $this->session->set_userdata($data);

                    redirect('user');
                } else {
                    // jika password salah
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Wrong Password!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                    redirect('auth');
                }
            } else {
                // jiak user tidak aktif
                $this->session->set_flashdata('message', '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                This email has not been activated! Check you email.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>');
                redirect('auth');
            }
        } else {
            // jika user tidak ada
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            This email is not registered!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>');
            redirect('auth');
        }
    }

    public function registration()
    {
        // rule  input form
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This email already used!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'Password dont match!',
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        // cek rule jika false kembalikan ke page register
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            //jika true, input data ke database dan kirim email verifikasi

            //get user inputs
            $name =  htmlspecialchars($this->input->post('name', true));
            $email = htmlspecialchars($this->input->post('email', true));
            //generate simple random code
            $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code = substr(str_shuffle($set), 0, 10);

            $data = [
                'nama' => $name,
                'email' => $email,
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 3,
                'is_active' => false,
                'date_created' => time(),
                'code' => $code
            ];

            $id = $this->user_model->insert($data);
            $e_email = base64_encode($email);

            $this->load->library('phpmailer_lib');

            // PHPMailer object
            $mail = $this->phpmailer_lib->load();

            // SMTP configuration
            $mail->SMTPDebug = 1;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'heheh3h33@gmail.com';
            $mail->Password   = 'hahahihi';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('heheh3h33@gmail.com', 'hoho');
            $mail->addAddress($this->input->post('email'));

            $mail->isHTML(true);



            $mail->Subject = 'Pleasev verify you email address';
            $message = "
                        <html>
                        <head>
                            <title>Verification Code</title>
                        </head>
                        <body>
                            <h2>Thank you for Registering.</h2>
                            <p>Your Account:</p>
                            <p>Name: " . $name . "</p>
                            <p>Email: " . $email . "</p>

                            <p>Please click the link below to activate your account.</p>
                            <h4><a href='" . base_url() . "auth/verify/" . $id . "/" . $code . "" . $e_email . "'>Activate My Account</a></h4>
                        </body>
                        </html>
                        ";
            $mail->Body = $message;

            $mail->send();

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Account has been created! Please login
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
            redirect('auth');
        }
    }

    public function forget()
    {        // rule input form forget password
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');


        //cek rule jika false maka di kembalikan ke page forget password
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forget');
            $this->load->view('templates/auth_footer');
        } else {
            //jika true
            //ambil imputan email dan encode untuk link
            $email = htmlspecialchars($this->input->post('email', true));
            $e_email = base64_encode($email);
            //generate simple random code
            $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code = substr(str_shuffle($set), 0, 10);




            $this->load->library('phpmailer_lib');

            // PHPMailer object
            $mail = $this->phpmailer_lib->load();

            // SMTP configuration
            $mail->SMTPDebug = 1;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'heheh3h33@gmail.com';
            $mail->Password   = 'hahahihi';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('heheh3h33@gmail.com', 'hoho');
            $mail->addAddress($this->input->post('email'));

            $mail->isHTML(true);



            $mail->Subject = 'Link to change your password is ready!';
            $message = "
                        <html>
                        <head>
                            <title>Change password</title>
                        </head>
                        <body>
                            <h2>Change password request from your account</h2>
                            <p>Email: " . $email . "</p>

                            <p>Please click the link below to redirecy to change your password.</p>
                            <h4><a href='" . base_url() . "auth/changepassword/" . $code . "" . $e_email . "'>Change my password</a></h4>
                        </body>
                        </html>
                        ";
            $mail->Body = $message;

            $mail->send();

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Email has been sent! please check your email!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
            redirect('auth');
        }
    }
    public function change()
    {
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'Password dont match!',
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        // cek rule jika false kembalikan ke page register
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Change Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/change');
            $this->load->view('templates/auth_footer');
        } else {
            //update user password
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = htmlspecialchars($this->input->post('email', true));
            $query = $this->user_model->changepass($password, $email);

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Password changed! Please login.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
            $this->session->unset_userdata('email');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        You have been log out!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
        redirect('auth');
    }

    public function changepassword()
    {

        $seg = $this->uri->segment(3);
        $code = substr($seg, 0, 10);
        $en_email = substr($seg, 10);


        $real_email =  base64_decode($en_email);
        //fetch user details
        $user = $this->user_model->getUserByEmail($real_email);

        //if code matches
        if ($user['email'] == $real_email) {
            $this->session->set_userdata($user);
            redirect('auth/change/');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">Validation Failed
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
        }
        redirect('auth');
    }

    public function verify()
    {
        $id =  $this->uri->segment(3);
        $seg = $this->uri->segment(4);
        $code = substr($seg, 0, 10);
        $en_email = substr($seg, 10);


        $real_email =  base64_decode($en_email);
        //fetch user details
        $user = $this->user_model->getUser($id);

        //if code matches
        if ($user['code'] == $code && $user['email'] == $real_email) {
            //update user active status
            $data = true;
            $query = $this->user_model->activate($data, $id);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">Validation Success
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">Validation Failed
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
        }
        redirect('auth');
    }

    public function block()
    {
        $this->load->view('auth/block');
    }
}
