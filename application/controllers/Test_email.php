<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_email extends CI_Controller {

    public function index() {
        $this->load->library('email', $this->config->item('email'));

        $this->email->from('mealrice517@gmail.com', 'MealRice Test');
        $this->email->to('adrianrusellr.tajan@gmail.com'); // replace with your email
        $this->email->subject('Test Email from CodeIgniter');
        $this->email->message('This is a test email using Gmail SMTP.');

        if ($this->email->send()) {
            echo "✅ Email sent successfully!";
        } else {
            echo "❌ Email failed to send!";
            echo "<pre>";
            echo $this->email->print_debugger();
            echo "</pre>";
        }
    }
}
