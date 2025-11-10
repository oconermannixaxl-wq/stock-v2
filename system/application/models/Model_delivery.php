<?php

class Model_delivery extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Fetch all or one delivery record
    public function getDeliveryData($id = null)
{
    if ($id) {
        $query = $this->db->get_where('delivery', ['id' => $id]);
        return $query->row_array();
    }

    $this->db->order_by('id', 'DESC');
    $query = $this->db->get('delivery');
    return $query->result_array();
}


    // Create a new delivery record
    public function create($order_id, $customer_name, $address)
    {
        $data = [
            'order_id' => $order_id,
            'customer_name' => $customer_name,
            'address' => $address,
            'status' => 'Packing'
        ];
        return $this->db->insert('delivery', $data);
    }

    // Update delivery status
    public function updateStatus($id, $status, $courier_name = null, $tracking_number = null)
    {
        $data = [
            'status' => $status,
            'courier_name' => $courier_name,
            'tracking_number' => $tracking_number
        ];

        $this->db->where('id', $id);
        return $this->db->update('delivery', $data);
    }
    public function getAllDeliveries()
{
    $this->db->order_by('id', 'DESC');
    $query = $this->db->get('delivery');
    return $query->result_array();
}
public function countTotalDeliveries()
{
    return $this->db->count_all('delivery');
}

}
