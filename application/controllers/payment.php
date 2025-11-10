<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in(); // ✅ Ensures only logged-in users can access
        
        $this->data['page_title'] = 'Payment Settings';

        $this->load->model('model_payment');
        $this->load->library(['session', 'upload']);
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $this->data['payment'] = $this->model_payment->getPaymentSettings();
        $this->render_template('payments/create', $this->data); // ✅ Loads from /views/payments/create.php
    }

    public function update()
    {
        $receiver_name = $this->input->post('receiver_name');
        $qr_code = '';

        // File upload setup
        if (!empty($_FILES['qr_code']['name'])) {
            $config = [
                'upload_path'   => './uploads/qr/',
                'allowed_types' => 'jpg|jpeg|png',
                'file_name'     => time() . '_' . $_FILES['qr_code']['name']
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('qr_code')) {
                $qr_code = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('payment');
            }
        }

        // Update database
        $this->model_payment->updatePaymentSettings($receiver_name, $qr_code);
        $this->session->set_flashdata('success', '✅ Payment settings updated successfully.');
        redirect('payment');
    }
}
