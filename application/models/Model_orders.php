<?php 

class Model_orders extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('pdf'); // Make sure Dompdf or TCPDF is loaded
    }

    /* ------------------ COUNT TOTAL PAID ORDERS ------------------ */
    public function countTotalPaidOrders()
    {
        $this->db->where('paid_status', 1); // 1 = Paid
        $this->db->from('orders');
        return $this->db->count_all_results();
    }

    /* ------------------ GET ORDERS ------------------ */
    public function getOrdersData($id = null)
    {
        if ($id) {
            $query = $this->db->get_where('orders', ['id' => $id]);
            return $query->row_array();
        }

        $query = $this->db->order_by('id', 'DESC')->get('orders');
        return $query->result_array();
    }

    /* Get order items */
    public function getOrdersItemData($order_id = null)
    {
        if (!$order_id) return false;

        $query = $this->db->get_where('orders_item', ['order_id' => $order_id]);
        return $query->result_array();
    }

    /* Count total items in an order */
    public function countOrderItem($order_id)
    {
        $this->db->where('order_id', $order_id);
        $this->db->from('orders_item');
        return $this->db->count_all_results();
    }

    /* ------------------ CREATE ORDER ------------------ */
    public function create()
    {
        $user_id = $this->session->userdata('id');
        $bill_no = 'BILPR-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));

        $data = array(
            'bill_no' => $bill_no,
            'customer_name' => $this->input->post('customer_name'),
            'customer_address' => $this->input->post('customer_address'),
            'customer_phone' => $this->input->post('customer_phone'),
            'customer_email' => $this->input->post('customer_email'),
            'date_time' => strtotime(date('Y-m-d H:i:s')),
            'gross_amount' => $this->input->post('gross_amount_value'),
            'service_charge_rate' => $this->input->post('service_charge_rate'),
            'service_charge' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value') : 0,
            'vat_charge_rate' => $this->input->post('vat_charge_rate'),
            'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
            'net_amount' => $this->input->post('net_amount_value'),
            'discount' => $this->input->post('discount'),
            'paid_status' => 2, // Unpaid
            'user_id' => $user_id
        );

        $this->db->insert('orders', $data);
        $order_id = $this->db->insert_id();

        $this->_saveOrderItems($order_id);

        // Auto-create delivery record
        if ($order_id) {
            $this->load->model('model_delivery');
            $this->model_delivery->create(
                $order_id,
                $this->input->post('customer_name'),
                $this->input->post('customer_address')
            );
        }

        // Generate PDF and send email
        if (!empty($data['customer_email'])) {
            $this->_generate_pdf($data, $order_id);
            $this->_send_receipt_email($data);
        }

        return $order_id;
    }

    /* ------------------ UPDATE ORDER ------------------ */
    public function update($order_id)
    {
        if (!$order_id) return false;

        $user_id = $this->session->userdata('id');

        $data = array(
            'customer_name' => $this->input->post('customer_name'),
            'customer_address' => $this->input->post('customer_address'),
            'customer_phone' => $this->input->post('customer_phone'),
            'customer_email' => $this->input->post('customer_email'),
            'gross_amount' => $this->input->post('gross_amount_value'),
            'service_charge_rate' => $this->input->post('service_charge_rate'),
            'service_charge' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value') : 0,
            'vat_charge_rate' => $this->input->post('vat_charge_rate'),
            'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
            'net_amount' => $this->input->post('net_amount_value'),
            'discount' => $this->input->post('discount'),
            'paid_status' => $this->input->post('paid_status'),
            'user_id' => $user_id
        );

        // Restore stock for old items
        $this->load->model('model_products');
        $old_items = $this->getOrdersItemData($order_id);
        foreach ($old_items as $item) {
            $product = $this->model_products->getProductData($item['product_id']);
            $restored_qty = (int)$product['qty'] + (int)$item['qty'];
            $this->model_products->update(['qty' => $restored_qty], $item['product_id']);
        }

        // Delete old order items
        $this->db->where('order_id', $order_id);
        $this->db->delete('orders_item');

        // Update order
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data);

        // Insert new items and deduct stock
        $this->_saveOrderItems($order_id);

        return true;
    }

    /* ------------------ DELETE ORDER ------------------ */
    public function delete($order_id)
    {
        if (!$order_id) return false;

        $this->load->model('model_products');
        $items = $this->getOrdersItemData($order_id);

        // Restore stock
        foreach ($items as $item) {
            $product = $this->model_products->getProductData($item['product_id']);
            $restored_qty = (int)$product['qty'] + (int)$item['qty'];
            $this->model_products->update(['qty' => $restored_qty], $item['product_id']);
        }

        // Delete order items
        $this->db->where('order_id', $order_id);
        $this->db->delete('orders_item');

        // Delete order
        $this->db->where('id', $order_id);
        return $this->db->delete('orders');
    }

    /* ------------------ HELPER: SAVE ORDER ITEMS ------------------ */
    private function _saveOrderItems($order_id)
    {
        $this->load->model('model_products');

        $count = count($this->input->post('product'));
        for ($i = 0; $i < $count; $i++) {
            $item = [
                'order_id' => $order_id,
                'product_id' => $this->input->post('product')[$i],
                'qty' => $this->input->post('qty')[$i],
                'rate' => $this->input->post('rate_value')[$i],
                'amount' => $this->input->post('amount_value')[$i],
            ];
            $this->db->insert('orders_item', $item);

            // Deduct stock
            $product = $this->model_products->getProductData($this->input->post('product')[$i]);
            $new_qty = (int)$product['qty'] - (int)$this->input->post('qty')[$i];
            $this->model_products->update(['qty' => $new_qty], $this->input->post('product')[$i]);
        }
    }

    /* ------------------ GENERATE PDF ------------------ */
   /* ------------------ GENERATE PDF ------------------ */
private function _generate_pdf($order_data, $order_id)
{
    $items = $this->getOrdersItemData($order_id);
    $payment = $this->db->get_where('payment_settings', ['id' => 1])->row_array();
    $receiver_name = isset($payment['receiver_name']) ? $payment['receiver_name'] : '';

    // Build QR Code base64 if exists
    $qr_code_img_tag = '';
    $qr_code_file = FCPATH . 'uploads/qr/' . (isset($payment['qr_code']) ? $payment['qr_code'] : '');
    if (!empty($payment['qr_code']) && file_exists($qr_code_file)) {
        $type = pathinfo($qr_code_file, PATHINFO_EXTENSION);
        $data = file_get_contents($qr_code_file);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        // QR image + receiver name box
        $qr_code_img_tag = '<div class="qr-block">
                                <div class="receiver">' . htmlspecialchars($receiver_name) . '</div>
                                <img src="' . $base64 . '" class="qr" alt="QR Code">
                            </div>';
    }

    $date = date('d/m/Y');

    // Start HTML with inline styles (Dompdf-friendly)
    $html = '
    <html>
    <head>
      <meta charset="utf-8">
      <style>
        /* Reset */
        body { font-family: Arial, Helvetica, sans-serif; color: #222; margin: 0; padding: 20px; font-size: 12px; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e6e6e6; padding-bottom: 10px; margin-bottom: 12px; }
        .company { line-height: 1; }
        .company h1 { margin: 0; font-size: 20px; letter-spacing: 1px; }
        .company small { display: block; font-size: 11px; color: #666; margin-top: 4px; }

        .meta { text-align: right; font-size: 12px; color: #333; }
        .meta .bill { font-weight: bold; font-size: 13px; color: #000; }

        .customer-and-qr { display:flex; justify-content:space-between; align-items:flex-start; margin-top: 12px; }
        .customer { width: 60%; border: 1px solid #eee; padding: 8px; border-radius: 4px; }
        .customer b { display:block; margin-bottom:6px; }
        .qr-block { width: 35%; text-align:center; border: 1px solid #eee; padding: 8px; border-radius: 4px; }
        .qr-block .receiver { font-weight: bold; margin-bottom:8px; }
        .qr { max-width:150px; height:auto; }

        table.items { width:100%; border-collapse: collapse; margin-top: 16px; }
        table.items th, table.items td { border:1px solid #e6e6e6; padding: 8px; text-align:left; font-size:12px; }
        table.items th { background:#f7f7f7; font-weight:600; }

        .totals { margin-top: 12px; float: right; width: 320px; }
        .totals table { width:100%; border-collapse: collapse; }
        .totals th, .totals td { padding: 6px 8px; text-align: left; }
        .totals tr.total-row th { font-size: 14px; font-weight:700; }
        .foot-note { clear: both; margin-top: 30px; font-size: 11px; color:#666; }
        .notice { margin-top: 10px; padding:8px; background:#fafafa; border-left:4px solid #2196F3; font-size: 12px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <div class="company">
            <h1>' . htmlspecialchars($this->model_company->getCompanyData(1)['company_name'] ?? 'Company') . '</h1>
            <small>Official Receipt / Invoice</small>
          </div>
          <div class="meta">
            <div>Date: ' . $date . '</div>
            <div class="bill">Bill ID: ' . htmlspecialchars($order_data['bill_no']) . '</div>
          </div>
        </div>

        <div class="customer-and-qr">
          <div class="customer">
            <b>Customer</b>
            <div><strong>Name:</strong> ' . htmlspecialchars($order_data['customer_name']) . '</div>
            <div><strong>Address:</strong> ' . nl2br(htmlspecialchars($order_data['customer_address'])) . '</div>
            <div><strong>Phone:</strong> ' . htmlspecialchars($order_data['customer_phone']) . '</div>
            ' . (!empty($order_data['customer_email']) ? '<div><strong>Email:</strong> ' . htmlspecialchars($order_data['customer_email']) . '</div>' : '') . '
          </div>

          ' . $qr_code_img_tag . '
        </div>

        <table class="items">
          <thead>
            <tr>
              <th style="width:55%;">Product</th>
              <th style="width:15%;">Price</th>
              <th style="width:10%;">Qty</th>
              <th style="width:20%;">Amount</th>
            </tr>
          </thead>
          <tbody>';

    // items rows
    foreach ($items as $item) {
        $product = $this->db->get_where('products', ['id' => $item['product_id']])->row_array();
        $product_name = $product['name'] ?? 'Item';
        $rate = number_format((float)$item['rate'], 2);
        $qty = (int)$item['qty'];
        $amount = number_format((float)$item['amount'], 2);

        $html .= '<tr>
                    <td>' . htmlspecialchars($product_name) . '</td>
                    <td>₱' . $rate . '</td>
                    <td>' . $qty . '</td>
                    <td>₱' . $amount . '</td>
                  </tr>';
    }

    // totals block
    $gross = number_format((float)$order_data['gross_amount'], 2);
    $service = number_format((float)$order_data['service_charge'], 2);
    $vat = number_format((float)$order_data['vat_charge'], 2);
    $discount = number_format((float)$order_data['discount'], 2);
    $net = number_format((float)$order_data['net_amount'], 2);

    $html .= '</tbody></table>

        <div class="totals">
          <table>
            <tr><th>Gross Amount:</th><td>₱' . $gross . '</td></tr>';
    if (!empty($order_data['service_charge']) && (float)$order_data['service_charge'] > 0) {
        $html .= '<tr><th>Service (' . htmlspecialchars($order_data['service_charge_rate']) . '%):</th><td>₱' . $service . '</td></tr>';
    }
    if (!empty($order_data['vat_charge']) && (float)$order_data['vat_charge'] > 0) {
        $html .= '<tr><th>VAT (' . htmlspecialchars($order_data['vat_charge_rate']) . '%):</th><td>₱' . $vat . '</td></tr>';
    }
    $html .= '<tr><th>Discount:</th><td>₱' . $discount . '</td></tr>
            <tr class="total-row"><th>Net Amount:</th><td>₱' . $net . '</td></tr>
          </table>
        </div>

        <div class="foot-note">
          <div class="notice">
            <strong>Payment info:</strong> Scan the QR code on the right to pay via GCash. Please include Bill ID in payment notes.
          </div>
          <p style="margin-top:12px;">Thank you for your business! If you have questions about this invoice, email us at ' . htmlspecialchars($this->model_company->getCompanyData(1)['company_email'] ?? 'support@example.com') . '.</p>
        </div>

      </div>
    </body>
    </html>';

    // render and save PDF
    $pdf_path = FCPATH . 'uploads/' . $order_data['bill_no'] . '.pdf';

    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'portrait');
    $this->pdf->render();
    file_put_contents($pdf_path, $this->pdf->output());
}




    /* ------------------ SEND EMAIL ------------------ */
  private function _send_receipt_email($order_data)
{
    // Path to PDF file
    $pdf_path = FCPATH . 'uploads/' . $order_data['bill_no'] . '.pdf';

    // Generate URL to access PDF
    // Make sure 'uploads' is publicly accessible or serve via a controller
    $pdf_url = base_url('uploads/' . $order_data['bill_no'] . '.pdf');

    $config = [
        'protocol'    => 'smtp',
        'smtp_host'   => 'smtp.gmail.com',
        'smtp_port'   => 587,
        'smtp_user'   => 'mealrice517@gmail.com',
        'smtp_pass'   => 'aqjv jecp vgip ajly',
        'smtp_crypto' => 'tls',
        'mailtype'    => 'html',
        'charset'     => 'utf-8',
        'newline'     => "\r\n",
        'wordwrap'    => TRUE,
        'validate'    => TRUE
    ];

    $this->email->initialize($config);
    $this->email->from('mealrice517@gmail.com', 'MealRice Shop');
    $this->email->to($order_data['customer_email']);
    $this->email->subject('Your Order Receipt - ' . $order_data['bill_no']);

    $message = '<h2>Thank you for your order, ' . $order_data['customer_name'] . '!</h2>
    <p><strong>Bill No:</strong> ' . $order_data['bill_no'] . '</p>
    <p><strong>Total Amount:</strong> ₱' . number_format($order_data['net_amount'], 2) . '</p>
    <p>Your official receipt is available here: <a href="' . $pdf_url . '" target="_blank">Download PDF</a></p>
    <br>
    <p>We will notify you once your order is being prepared!</p>
    <p>- MealRice Team</p>';

    $this->email->message($message);

    if ($this->email->send()) {
        log_message('info', '✅ Order receipt link sent to ' . $order_data['customer_email']);
    } else {
        log_message('error', $this->email->print_debugger());
    }
}



    }

