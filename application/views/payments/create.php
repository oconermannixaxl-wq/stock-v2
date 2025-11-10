<div class="content-wrapper">
  <section class="content-header">
    <h1>Payment Settings</h1>
  </section>
  <section class="content">
    <?php if($this->session->flashdata('success')): ?>
      <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <form action="<?php echo base_url('payment/update'); ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label>Receiver Name</label>
        <input type="text" name="receiver_name" class="form-control" value="<?php echo $payment['receiver_name'] ?? ''; ?>" required>
      </div>

      <div class="form-group">
        <label>Upload QR Code</label>
        <input type="file" name="qr_code" class="form-control">
        <?php if(!empty($payment['qr_code'])): ?>
          <img src="<?php echo base_url('uploads/qr/'.$payment['qr_code']); ?>" alt="QR" width="150" class="mt-2">
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-primary">Update Payment Settings</button>
    </form>
  </section>
</div>
