<?php
class Model_payment extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPaymentSettings()
    {
        $query = $this->db->get('payment_settings');
        return $query->row_array();
    }

    public function updatePaymentSettings($receiver_name, $qr_code)
    {
        $data = ['receiver_name' => $receiver_name];
        if ($qr_code != '') {
            $data['qr_code'] = $qr_code;
        }

        $this->db->update('payment_settings', $data);
        return $this->db->affected_rows();
    }

    public function countTotalPayments()
    {
        return $this->db->count_all('payment_settings');
    }
}
