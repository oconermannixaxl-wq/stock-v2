<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->data['page_title'] = 'Orders';

        $this->load->model('model_orders');
        $this->load->model('model_products');
        $this->load->model('model_company');
    }

    public function index()
    {
        if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->data['page_title'] = 'Manage Orders';
        $this->render_template('orders/index', $this->data);       
    }

    public function fetchOrdersData()
    {
        $result = array('data' => array());

        $data = $this->model_orders->getOrdersData();

        foreach ($data as $key => $value) {
            $count_total_item = $this->model_orders->countOrderItem($value['id']);
            $date_time = date('d-m-Y h:i a', $value['date_time']);

            $buttons = '';
            if(in_array('viewOrder', $this->permission)) {
                $buttons .= '<a target="__blank" href="'.base_url('orders/printDiv/'.$value['id']).'" class="btn btn-default"><i class="fa fa-print"></i></a>';
            }
            if(in_array('updateOrder', $this->permission)) {
                $buttons .= ' <a href="'.base_url('orders/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }
            if(in_array('deleteOrder', $this->permission)) {
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }

            $paid_status = ($value['paid_status'] == 1) ? '<span class="label label-success">Paid</span>' : '<span class="label label-warning">Not Paid</span>';

            $result['data'][$key] = array(
    $value['bill_no'],
    $value['customer_name'],
    $value['customer_phone'],
    $date_time,
    $count_total_item,
    number_format((float)$value['net_amount'], 2), // <-- cast to float
    $paid_status,
    $buttons
);

        }

        echo json_encode($result);
    }


    public function create()
    {
        if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->data['page_title'] = 'Add Order';
        $this->form_validation->set_rules('product[]', 'Product name', 'trim|required');

        if ($this->form_validation->run() == TRUE) {            
            $order_id = $this->model_orders->create();
            
            if($order_id) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('orders/update/'.$order_id, 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('orders/create/', 'refresh');
            }
        } else {
            $company = $this->model_company->getCompanyData(1);
            $this->data['company_data'] = $company;
            $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0);
            $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0);

            $this->data['products'] = $this->model_products->getActiveProductData();      
            $this->render_template('orders/create', $this->data);
        }   
    }

    public function getProductValueById()
    {
        $product_id = $this->input->post('product_id');
        if($product_id) {
            $product_data = $this->model_products->getProductData($product_id);
            echo json_encode($product_data);
        }
    }

    public function getTableProductRow()
    {
        $products = $this->model_products->getActiveProductData();
        echo json_encode($products);
    }

    public function update($id)
    {
        if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$id) redirect('dashboard', 'refresh');

        $this->data['page_title'] = 'Update Order';
        $this->form_validation->set_rules('product[]', 'Product name', 'trim|required');

        if ($this->form_validation->run() == TRUE) {            
            $update = $this->model_orders->update($id);
            if($update) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('orders/update/'.$id, 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('orders/update/'.$id, 'refresh');
            }
        } else {
            $company = $this->model_company->getCompanyData(1);
            $this->data['company_data'] = $company;
            $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0);
            $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0);

            $orders_data = $this->model_orders->getOrdersData($id);
            $orders_item = $this->model_orders->getOrdersItemData($orders_data['id']);
            $result['order'] = $orders_data;
            $result['order_item'] = $orders_item;

            $this->data['order_data'] = $result;
            $this->data['products'] = $this->model_products->getActiveProductData();      
            $this->render_template('orders/edit', $this->data);
        }
    }

    public function remove()
    {
        if(!in_array('deleteOrder', $this->permission)) redirect('dashboard', 'refresh');

        $order_id = $this->input->post('order_id');
        $response = array();

        if($order_id) {
            // Fixed method name to match model
            $delete = $this->model_orders->delete($order_id);
            if($delete) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the order";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refresh the page again!!";
        }

        echo json_encode($response); 
    }
    public function getProductDetailsById()
{
    $product_id = $this->input->post('product_id');

    if ($product_id) {
        $product_data = $this->model_products->getProductData($product_id);
        echo json_encode($product_data);
    } else {
        echo json_encode(['error' => 'Invalid product ID']);
    }
}


  public function printDiv($id)
{
    if(!in_array('viewOrder', $this->permission)) redirect('dashboard', 'refresh');

    if($id) {
        $order_data = $this->model_orders->getOrdersData($id);
        $orders_items = $this->model_orders->getOrdersItemData($id);
        $company_info = $this->model_company->getCompanyData(1);

        $order_date = date('d/m/Y', $order_data['date_time']);
        $paid_status = ($order_data['paid_status'] == 1) 
            ? '<span style="color:green;font-weight:bold;">Paid</span>' 
            : '<span style="color:red;font-weight:bold;">Unpaid</span>';

        // Build HTML for print
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Invoice</title>
        <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
        <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
        <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'"></head>
        <body onload="window.print();"><div class="wrapper"><section class="invoice">
        <div class="row"><div class="col-xs-12"><h2 class="page-header">'.$company_info['company_name'].' <small class="pull-right">Date: '.$order_date.'</small></h2></div></div>
        <div class="row invoice-info"><div class="col-sm-4 invoice-col">
        <b>Bill ID:</b> '.$order_data['bill_no'].'<br>
        <b>Name:</b> '.$order_data['customer_name'].'<br>
        <b>Address:</b> '.$order_data['customer_address'].'<br>
        <b>Phone:</b> '.$order_data['customer_phone'].'</div></div>
        <div class="row"><div class="col-xs-12 table-responsive"><table class="table table-striped"><thead><tr>
        <th>Product name</th><th>Price</th><th>Qty</th><th>Amount</th></tr></thead><tbody>';

        foreach ($orders_items as $v) {
            $product_data = $this->model_products->getProductData($v['product_id']); 
            $html .= '<tr><td>'.$product_data['name'].'</td><td>'.$v['rate'].'</td><td>'.$v['qty'].'</td><td>'.$v['amount'].'</td></tr>';
        }

        $html .= '</tbody></table></div></div>
        <div class="row"><div class="col-xs-6 pull pull-right"><div class="table-responsive"><table class="table">
        <tr><th style="width:50%">Gross Amount:</th><td>'.$order_data['gross_amount'].'</td></tr>';

        if($order_data['service_charge'] > 0) {
            $html .= '<tr><th>Service Charge ('.$order_data['service_charge_rate'].'%)</th><td>'.$order_data['service_charge'].'</td></tr>';
        }
        if($order_data['vat_charge'] > 0) {
            $html .= '<tr><th>Vat Charge ('.$order_data['vat_charge_rate'].'%)</th><td>'.$order_data['vat_charge'].'</td></tr>';
        }

        $html .= '
        <tr><th>Net Amount:</th><td>'.$order_data['net_amount'].'</td></tr>
        <tr><th>Paid Status:</th><td>'.$paid_status.'</td></tr>
        </table></div></div></div></section></div></body></html>';

        echo $html;
    }
}

    }

