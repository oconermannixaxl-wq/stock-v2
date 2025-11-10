<!-- Product Description Modal -->
<div class="modal fade" id="productDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="productDescriptionModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 10px; border: 2px solid #D4A017;">
      <div class="modal-header" style="background-color: #F2D7B6; border-bottom: 1px solid #D4A017;">
        <h4 class="modal-title" id="productDescriptionModalLabel" style="color: #4A3728;">
          <i class="fa fa-info-circle"></i> Product Details
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" 
                style="color: #4A3728; font-size: 24px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="background-color: #FFF8E1;">
        <p><strong>Name:</strong> <span id="modal_product_name"></span></p>
        <p><strong>Price:</strong> ₱<span id="modal_product_price"></span></p>
        <p><strong>Stock:</strong> <span id="modal_product_qty"></span></p>
        <p><strong>Description:</strong></p>
        <p id="modal_product_description" style="color: #4A3728; background-color: #FDF6E3; padding: 10px; border-radius: 6px; border: 1px solid #D4A017;"></p>
      </div>
      <div class="modal-footer" style="background-color: #F2D7B6; border-top: 1px solid #D4A017;">
        <button type="button" class="btn btn-warning" data-dismiss="modal" style="background-color:#8B6F47; border-color:#6B4E31; color:#FFF8E1;">
          <i class="fa fa-times"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 style="color: #4A3728;">
      Manage
      <small style="color: #8B6F47;">Orders</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" style="color: #8B6F47;"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active" style="color: #4A3728;">Orders</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert" style="background-color: #D4A017; border-color: #B58900; color: #4A3728;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert" style="background-color: #F2D7B6; border-color: #D4A017; color: #4A3728;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>

        <div class="box" style="border: 1px solid #D4A017; box-shadow: 0 2px 4px rgba(0,0,0,0.1); background-color: #FFF8E1;">
          <div class="box-header" style="background-color: #F2D7B6; border-bottom: 1px solid #D4A017;">
            <h3 class="box-title" style="color: #4A3728;">Add Order</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
            <div class="box-body" style="background-color: #FFF8E1;">
              <?php echo validation_errors(); ?>

              <div class="form-group">
                <label for="gross_amount" class="col-sm-12 control-label" style="color: #4A3728;">Date: <?php echo date('Y-m-d') ?></label>
              </div>
              <div class="form-group">
                <label for="gross_amount" class="col-sm-12 control-label" style="color: #4A3728;">Time: <?php echo date('h:i a') ?></label>
              </div>

              <div class="col-md-4 col-xs-12 pull pull-left">
                <div class="form-group">
                  <label for="customer_name" class="col-sm-5 control-label" style="text-align:left; color: #4A3728;">Customer Name</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer Name" autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;" />
                  </div>
                </div>

                <div class="form-group">
                  <label for="customer_address" class="col-sm-5 control-label" style="text-align:left; color: #4A3728;">Customer Address</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="customer_address" name="customer_address" placeholder="Enter Customer Address" autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
                  </div>
                </div>

                <div class="form-group">
                  <label for="customer_phone" class="col-sm-5 control-label" style="text-align:left; color: #4A3728;">Customer Phone</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" placeholder="Enter Customer Phone" autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
                  </div>
                </div>
              </div>
              <div class="form-group">
  <label for="customer_email" class="col-sm-5 control-label" style="text-align:left; color: #4A3728;">Customer Email</label>
  <div class="col-sm-7">
    <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Enter Customer Email" autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
  </div>
</div>


              <br /> <br/>
              <table class="table table-bordered" id="product_info_table" style="border-color: #D4A017; background-color: #FFF8E1;">
                <thead>
                  <tr style="background-color: #F2D7B6; color: #4A3728;">
                    <th style="width:50%">Product</th>
                    <th style="width:10%">Qty per Stack</th>
                    <th style="width:10%">Rate</th>
                    <th style="width:20%">Amount</th>
                    <th style="width:10%"><button type="button" id="add_row" class="btn btn-default" style="background-color: #D4A017; color: #4A3728; border-color: #B58900;"><i class="fa fa-plus"></i></button></th>
                  </tr>
                </thead>
                <tbody>
                  <tr id="row_1">
  <td style="display: flex; align-items: center; gap: 5px;">
    <select class="form-control select_group product" data-row-id="row_1" id="product_1" name="product[]" 
            style="width: 85%; border-color: #D4A017; background-color: #FDF6E3;" 
            onchange="getProductData(1)" required>
      <option value=""></option>
      <?php foreach ($products as $k => $v): ?>
        <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
      <?php endforeach ?>
    </select>
    <button type="button" class="btn btn-info" id="view_btn_1" 
            onclick="viewProductDescription(1)" 
            style="background-color:#8B6F47; border-color:#6B4E31; color:#FFF8E1;">
      <i class="fa fa-eye"></i>
    </button>
  </td>
  <td><input type="text" name="qty[]" id="qty_1" class="form-control" required onkeyup="getTotal(1)" style="border-color: #D4A017; background-color: #FDF6E3;"></td>
  <td>
    <input type="text" name="rate[]" id="rate_1" class="form-control" disabled autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
    <input type="hidden" name="rate_value[]" id="rate_value_1" class="form-control" autocomplete="off">
  </td>
  <td>
    <input type="text" name="amount[]" id="amount_1" class="form-control" disabled autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
    <input type="hidden" name="amount_value[]" id="amount_value_1" class="form-control" autocomplete="off">
  </td>
  <td><button type="button" class="btn btn-default" onclick="removeRow('1')" style="background-color: #D4A017; color: #4A3728; border-color: #B58900;"><i class="fa fa-close"></i></button></td>
</tr>

                </tbody>
              </table>

              <br /> <br/>

              <div class="col-md-6 col-xs-12 pull pull-right">
                <div class="form-group">
                  <label for="gross_amount" class="col-sm-5 control-label" style="color: #4A3728;">Gross Amount</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
                    <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                  </div>
                </div>
                <?php if($is_service_enabled == true): ?>
                <div class="form-group">
                  <label for="service_charge" class="col-sm-5 control-label" style="color: #4A3728;">S-Charge <?php echo $company_data['service_charge_value'] ?> %</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="service_charge" name="service_charge" disabled autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
                    <input type="hidden" class="form-control" id="service_charge_value" name="service_charge_value" autocomplete="off">
                  </div>
                </div>
                <?php endif; ?>
                <?php if($is_vat_enabled == true): ?>
                <div class="form-group">
                  <label for="vat_charge" class="col-sm-5 control-label" style="color: #4A3728;">Vat <?php echo $company_data['vat_charge_value'] ?> %</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="vat_charge" name="vat_charge" disabled autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
                    <input type="hidden" class="form-control" id="vat_charge_value" name="vat_charge_value" autocomplete="off">
                  </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                  <label for="discount" class="col-sm-5 control-label" style="color: #4A3728;"></label>
                  <div class="col-sm-7">
                    <input type="hidden" class="form-control" id="discount" name="discount" placeholder="Discount" onkeyup="subAmount()" autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
                  </div>
                </div>
                <div class="form-group">
                  <label for="net_amount" class="col-sm-5 control-label" style="color: #4A3728;">Net Amount</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="net_amount" name="net_amount" disabled autocomplete="off" style="border-color: #D4A017; background-color: #FDF6E3;">
                    <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" autocomplete="off">
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer" style="background-color: #F2D7B6; border-top: 1px solid #D4A017;">
  <input type="hidden" name="service_charge_rate" value="<?php echo $company_data['service_charge_value'] ?>" autocomplete="off">
  <input type="hidden" name="vat_charge_rate" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off">

  <button type="submit" class="btn btn-primary" 
          style="background-color: #D4A017; border-color: #B58900; color: #4A3728;">
          <i class="fa fa-plus-circle"></i> Create Order
  </button>


  <a href="<?php echo base_url('orders/') ?>" 
     class="btn btn-warning" 
     style="background-color: #8B6F47; border-color: #6B4E31; color: #FFF8E1;">
     <i class="fa fa-arrow-left"></i> Back
  </a>
</div>

          </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    $(".select_group").select2();
    $("#mainOrdersNav").addClass('active');
    $("#addOrderNav").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
  
    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1;

      $.ajax({
          url: base_url + '/orders/getTableProductRow/',
          type: 'post',
          dataType: 'json',
          success:function(response) {
               var html = '<tr id="row_'+row_id+'">'+
                   '<td>'+ 
                    '<select class="form-control select_group product" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%; border-color: #D4A017; background-color: #FDF6E3;" onchange="getProductData('+row_id+')">'+
                        '<option value=""></option>';
                        $.each(response, function(index, value) {
                          html += '<option value="'+value.id+'">'+value.name+'</option>';             
                        });
                        
                      html += '</select>'+
                    '</td>'+ 
                    '<td><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')" style="border-color: #D4A017; background-color: #FDF6E3;"></td>'+
                    '<td><input type="text" name="rate[]" id="rate_'+row_id+'" class="form-control" disabled style="border-color: #D4A017; background-color: #FDF6E3;"><input type="hidden" name="rate_value[]" id="rate_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="text" name="amount[]" id="amount_'+row_id+'" class="form-control" disabled style="border-color: #D4A017; background-color: #FDF6E3;"><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control"></td>'+
                    '<td><button type="button" class="btn btn-default" onclick="removeRow(\''+row_id+'\')" style="background-color: #D4A017; color: #4A3728; border-color: #B58900;"><i class="fa fa-close"></i></button></td>'+
                    '</tr>';

                if(count_table_tbody_tr >= 1) {
                $("#product_info_table tbody tr:last").after(html);  
              }
              else {
                $("#product_info_table tbody").html(html);
              }

              $(".product").select2();

          }
        });

      return false;
    });

  }); // /document

  function getTotal(row = null) {
    if(row) {
      var total = Number($("#rate_value_"+row).val()) * Number($("#qty_"+row).val());
      total = total.toFixed(2);
      $("#amount_"+row).val(total);
      $("#amount_value_"+row).val(total);
      
      subAmount();

    } else {
      alert('no row !! please refresh the page');
    }
  }

  function getProductData(row_id) {
    var product_id = $("#product_"+row_id).val();    
    if(product_id == "") {
      $("#rate_"+row_id).val("");
      $("#rate_value_"+row_id).val("");
      $("#qty_"+row_id).val("");           
      $("#amount_"+row_id).val("");
      $("#amount_value_"+row_id).val("");
    } else {
      $.ajax({
        url: base_url + 'orders/getProductValueById',
        type: 'post',
        data: {product_id : product_id},
        dataType: 'json',
        success:function(response) {
          $("#rate_"+row_id).val(response.price);
          $("#rate_value_"+row_id).val(response.price);
          $("#qty_"+row_id).val(1);
          $("#qty_value_"+row_id).val(1);
          var total = Number(response.price) * 1;
          total = total.toFixed(2);
          $("#amount_"+row_id).val(total);
          $("#amount_value_"+row_id).val(total);
          
          subAmount();
        }
      });
    }
  }

  function subAmount() {
    var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value']:0; ?>;
    var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value']:0; ?>;

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);
      totalSubAmount = Number(totalSubAmount) + Number($("#amount_"+count).val());
    }

    totalSubAmount = totalSubAmount.toFixed(2);
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);

    var vat = (Number($("#gross_amount").val())/100) * vat_charge;
    vat = vat.toFixed(2);
    $("#vat_charge").val(vat);
    $("#vat_charge_value").val(vat);

    var service = (Number($("#gross_amount").val())/100) * service_charge;
    service = service.toFixed(2);
    $("#service_charge").val(service);
    $("#service_charge_value").val(service);
    
    var totalAmount = (Number(totalSubAmount) + Number(vat) + Number(service));
    totalAmount = totalAmount.toFixed(2);

    var discount = $("#discount").val();
    if(discount) {
      var grandTotal = Number(totalAmount) - Number(discount);
      grandTotal = grandTotal.toFixed(2);
      $("#net_amount").val(grandTotal);
      $("#net_amount_value").val(grandTotal);
    } else {
      $("#net_amount").val(totalAmount);
      $("#net_amount_value").val(totalAmount);
    }
  }

  function removeRow(tr_id) {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    subAmount();
  }
  function viewProductDescription(row_id) {
  var product_id = $("#product_" + row_id).val();

  if (!product_id) {
    alert("Please select a product first!");
    return;
  }

  $.ajax({
    url: base_url + 'orders/getProductDetailsById',
    type: 'post',
    data: { product_id: product_id },
    dataType: 'json',
    success: function(response) {
      if (response) {
        var cleanDescription = response.description
          ? response.description.replace(/<\/?[^>]+(>|$)/g, "")
          : 'No description available.';

        $("#modal_product_name").text(response.name || 'N/A');
        $("#modal_product_price").text("₱" + (response.price || '0.00'));
        $("#modal_product_qty").text(response.qty || 'N/A');
        $("#modal_product_description").text(cleanDescription);

        $("#productDescriptionModal").modal('show');
      } else {
        alert("Product details not found!");
      }
    },
    error: function() {
      alert("Error fetching product details. Please check your server route.");
    }
  });
}


</script>