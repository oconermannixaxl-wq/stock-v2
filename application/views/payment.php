<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Payments</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Payments</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif ($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Payment Settings</h3>
          </div>
          <!-- /.box-header -->

          <form role="form" action="<?= base_url('payment/update') ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="form-group">
                <label for="receiver_name" class="col-sm-3 control-label" style="text-align:left;">Receiver Name</label>
                <div class="col-sm-9">
                  <input type="text" name="receiver_name" id="receiver_name" class="form-control"
                    value="<?= isset($payment['receiver_name']) ? htmlspecialchars($payment['receiver_name']) : '' ?>"
                    placeholder="Enter Receiver Name" required>
                </div>
              </div>

              <div class="form-group">
                <label for="qr_code" class="col-sm-3 control-label" style="text-align:left;">QR Code Image</label>
                <div class="col-sm-9">
                  <?php if (!empty($payment['qr_code'])): ?>
                    <img src="<?= base_url('uploads/qr/' . $payment['qr_code']) ?>" alt="QR Code"
                      style="width:150px; border:1px solid #ddd; margin-bottom:10px;">
                  <?php endif; ?>
                  <input type="file" name="qr_code" id="qr_code" accept="image/*" class="form-control">
                  <p style="font-size:12px; color:#777; margin-top:5px;">Upload a new QR code image (PNG/JPG).</p>
                </div>
              </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save Changes
              </button>
              <a href="<?= base_url('dashboard') ?>" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i> Back
              </a>
            </div>
          </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#mainPaymentNav").addClass('active');
    $("#managePaymentNav").addClass('active');
  });
</script>
