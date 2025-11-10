<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1 style="color: #4A3728;">
      Manage
      <small style="color: #8B6F47;">Delivery</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard') ?>" style="color: #8B6F47;"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active" style="color: #4A3728;">Delivery</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="box" style="border: 1px solid #D4A017; box-shadow: 0 2px 4px rgba(0,0,0,0.1); background-color: #FFF8E1;">
      <div class="box-header" style="background-color: #F2D7B6; border-bottom: 1px solid #D4A017;">
        <h3 class="box-title" style="color: #4A3728;">Delivery List</h3>
        <div class="box-tools pull-right">
          <a href="<?php echo base_url('delivery/create') ?>" class="btn btn-sm" style="background-color: #8B6F47; color: #FFF8E1; border: none;">
          </a>
        </div>
      </div>

      <div class="box-body" style="background-color: #FFF8E1;">
        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" style="background-color: #D4A017; border-color: #B58900; color: #4A3728;">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <?= $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" style="background-color: #F2D7B6; border-color: #D4A017; color: #4A3728;">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <?= $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>

        <div class="table-responsive">
          <table class="table table-bordered table-striped" style="border-color: #D4A017;">
            <thead>
              <tr style="background-color: #F2D7B6; color: #4A3728;">
                <th style="width: 10%;">Order ID</th>
                <th style="width: 20%;">Customer</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%;">Courier</th>
                <th style="width: 15%;">Tracking No.</th>
                <th style="width: 15%;">Last Updated</th>
                <th style="width: 10%;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($deliveries)): ?>
                <?php foreach ($deliveries as $d): ?>
                  <tr>
                    <td><?= htmlspecialchars($d['order_id']) ?></td>
                    <td><?= htmlspecialchars($d['customer_name']) ?></td>
                    <td>
                      <?php if($d['status'] == 'Packing'): ?>
                        <span class="label" style="background-color:#D4A017; color:#4A3728;">Packing</span>
                      <?php elseif($d['status'] == 'On Delivery'): ?>
                        <span class="label" style="background-color:#8B6F47; color:#FFF8E1;">On Delivery</span>
                      <?php elseif($d['status'] == 'Delivered'): ?>
                        <span class="label" style="background-color:#4A3728; color:#FFF8E1;">Delivered</span>
                      <?php else: ?>
                        <span class="label label-default"><?= htmlspecialchars($d['status']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($d['courier_name']) ?></td>
                    <td><?= htmlspecialchars($d['tracking_number']) ?></td>
                    <td><?= date('M d, Y h:i A', strtotime($d['updated_at'])) ?></td>
                    <td>
                      <a href="<?= base_url('delivery/edit/'.$d['id']) ?>" class="btn btn-sm" style="background-color:#8B6F47; color:#FFF8E1;">
                        <i class="fa fa-pencil"></i>
                      </a>
                     
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center" style="color:#4A3728;">No deliveries found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
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
