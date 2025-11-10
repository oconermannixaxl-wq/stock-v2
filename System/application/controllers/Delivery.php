<?php

class Delivery extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->data['page_title'] = 'Delivery Management';
        $this->load->model('model_delivery');
    }

    public function index()
    {
        $this->data['deliveries'] = $this->model_delivery->getDeliveryData();
        $this->render_template('delivery/index', $this->data);
    }

    // ✅ EDIT DELIVERY
    public function edit($id = null)
    {
        if ($id === null) {
            $this->session->set_flashdata('error', 'No delivery ID provided.');
            redirect('delivery/index');
            return;
        }

        $this->data['delivery'] = $this->model_delivery->getDeliveryData($id);

        if (empty($this->data['delivery'])) {
            $this->session->set_flashdata('error', 'Delivery record not found.');
            redirect('delivery/index');
            return;
        }

        $this->render_template('delivery/edit', $this->data);
    }
    public function update($id = null)
{
    if ($id === null) {
        $this->session->set_flashdata('error', 'Invalid delivery ID.');
        redirect('delivery/index');
        return;
    }

    // Get form data
    $status = $this->input->post('status');
    $courier_name = $this->input->post('courier_name');
    $tracking_number = $this->input->post('tracking_number');

    // Update in database
    $updated = $this->model_delivery->updateStatus($id, $status, $courier_name, $tracking_number);

    if ($updated) {
        $this->session->set_flashdata('success', 'Delivery updated successfully!');
    } else {
        $this->session->set_flashdata('error', 'Failed to update delivery. Please try again.');
    }

    redirect('delivery/index');
}
    

    // ✅ TRACK ALL DELIVERIES
    public function track($id = null)
{
    $this->data['page_title'] = 'Track Delivery';

    // ✅ If no specific ID is given, show all deliveries
    if ($id === null) {
        $this->data['deliveries'] = $this->model_delivery->getAllDeliveries();
        $this->render_template('delivery/track', $this->data);
        return;
    }

    // ✅ If a specific delivery ID is provided
    $delivery = $this->model_delivery->getDeliveryDataById($id);

    if (!$delivery) {
        $this->data['error'] = "No delivery found with ID: " . htmlspecialchars($id);
        $this->data['deliveries'] = []; // Empty table
    } else {
        $this->data['deliveries'] = [$delivery]; // Single record in array form
    }

    $this->render_template('delivery/track', $this->data);
}
}
