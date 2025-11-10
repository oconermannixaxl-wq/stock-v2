<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1 style="color: #4A3728;">
      Manage
      <small style="color: #8B6F47;">Edit Delivery</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard') ?>" style="color: #8B6F47;"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('delivery') ?>" style="color: #8B6F47;">Delivery</a></li>
      <li class="active" style="color: #4A3728;">Edit Delivery</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div id="messages"></div>

        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert" style="background-color: #D4A017; border-color: #B58900; color: #4A3728;">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <?= $this->session->flashdata('success'); ?>
          </div>
        <?php elseif ($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert" style="background-color: #F2D7B6; border-color: #D4A017; color: #4A3728;">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <?= $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($delivery)): ?>
          <div class="box" style="border: 1px solid #D4A017; box-shadow: 0 2px 4px rgba(0,0,0,0.1); background-color: #FFF8E1;">
            <div class="box-header" style="background-color: #F2D7B6; border-bottom: 1px solid #D4A017;">
              <h3 class="box-title" style="color: #4A3728;">Edit Delivery #<?= htmlspecialchars($delivery['id']) ?></h3>
            </div>

            <form method="post" action="<?= base_url('delivery/update/' . $delivery['id']) ?>">
              <div class="box-body" style="background-color: #FFF8E1;">
                
                <div class="form-group">
                  <label style="color: #4A3728;">Status</label>
                  <select name="status" class="form-control" style="border: 1px solid #D4A017;">
                    <option value="Packing" <?= ($delivery['status'] == 'Packing') ? 'selected' : '' ?>>Packing</option>
                    <option value="On Delivery" <?= ($delivery['status'] == 'On Delivery') ? 'selected' : '' ?>>On Delivery</option>
                    <option value="Delivered" <?= ($delivery['status'] == 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                  </select>
                </div>

                <div class="form-group">
                  <label style="color: #4A3728;">Courier Name</label>
                  <input type="text" name="courier_name" value="<?= htmlspecialchars($delivery['courier_name']) ?>" class="form-control" style="border: 1px solid #D4A017;">
                </div>

                <div class="form-group">
                  <label style="color: #4A3728;">Tracking Number</label>
                  <input type="text" name="tracking_number" value="<?= htmlspecialchars($delivery['tracking_number']) ?>" class="form-control" style="border: 1px solid #D4A017;">
                </div>

              </div>

              <div class="box-footer" style="background-color: #F2D7B6; border-top: 1px solid #D4A017;">
                <button type="submit" class="btn btn-success" style="background-color: #8B6F47; border-color: #6B4E31; color: #FFF8E1;">
                  <i class="fa fa-save"></i> Save Changes
                </button>
                
                <a href="<?php echo base_url('delivery') ?>" class="btn btn-default" style="border: 1px solid #D4A017; color: #4A3728;">
                  <i class="fa fa-arrow-left"></i> Back
                </a>
              </div>
            </form>
          </div>
        <?php else: ?>
          <div class="alert alert-warning" style="background-color: #F2D7B6; color: #4A3728; border-color: #D4A017;">
            No delivery data found. Please return to the delivery list.
          </div>
        <?php endif; ?>

      </div>
    </div>
  </section>
</div>
<!-- /.content-wrapper -->

<script>
  $(document).ready(function() {
    $("#mainDeliveryNav").addClass('active');
    $("#manageDeliveryNav").addClass('active');
  });
</script>
