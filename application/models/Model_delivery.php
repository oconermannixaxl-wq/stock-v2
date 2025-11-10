<?php

class Model_delivery extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Fetch all or one delivery record (only paid orders)
    public function getDeliveryData($id = null)
    {
        $this->db->select('delivery.*');
        $this->db->from('delivery');
        $this->db->join('orders', 'orders.id = delivery.order_id');
        $this->db->where('orders.paid_status', 1); // only show paid orders

        if ($id) {
            $this->db->where('delivery.id', $id);
            return $this->db->get()->row_array();
        }

        $this->db->order_by('delivery.id', 'DESC');
        return $this->db->get()->result_array();
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

    // Fetch all deliveries (only for paid orders)
    public function getAllDeliveries()
    {
        $this->db->select('delivery.*');
        $this->db->from('delivery');
        $this->db->join('orders', 'orders.id = delivery.order_id');
        $this->db->where('orders.paid_status', 1);
        $this->db->order_by('delivery.id', 'DESC');

        return $this->db->get()->result_array();
    }

    // Count only deliveries of paid orders
    public function countTotalDeliveries()
    {
        $this->db->from('delivery');
        $this->db->join('orders', 'orders.id = delivery.order_id');
        $this->db->where('orders.paid_status', 1);

        return $this->db->count_all_results();
    }
}
