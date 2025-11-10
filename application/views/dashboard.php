<!-- Content Wrapper -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Dashboard
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <section class="content">
    <?php if ($is_admin == true): ?>

      <!-- Admin-only stat boxes -->
      <div class="row">
        <?php
        $boxes = [
          ['value' => $total_products, 'label' => 'Total Products', 'bg' => 'bg-aqua', 'icon' => 'ion-bag', 'url' => 'products/'],
          ['value' => $total_paid_orders, 'label' => 'Paid Orders', 'bg' => 'bg-green', 'icon' => 'ion-stats-bars', 'url' => 'orders/'],
          ['value' => $total_users, 'label' => 'Total Users', 'bg' => 'bg-yellow', 'icon' => 'ion-android-people', 'url' => 'users/'],
          ['value' => $total_stores, 'label' => 'Total Stores', 'bg' => 'bg-red', 'icon' => 'ion-android-home', 'url' => 'stores/']
        ];
        foreach ($boxes as $b): ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box <?=$b['bg']?> hover-lift">
              <div class="inner">
                <h3><?=number_format($b['value'])?></h3>
                <p><?=$b['label']?></p>
              </div>
              <div class="icon"><i class="ion <?=$b['icon']?>"></i></div>
              <a href="<?=base_url($b['url'])?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <!-- /.row -->

    <?php endif; ?>

    <!-- Product Carousel (Visible to ALL users) -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-star"></i> Featured Products</h3>
          </div>
          <div class="box-body">

            <div id="productCarousel" class="carousel slide" data-ride="carousel" data-interval="4000">
              <!-- Indicators -->
              <ol class="carousel-indicators">
                <?php foreach ($product_list as $i => $p): ?>
                  <li data-target="#productCarousel" data-slide-to="<?=$i?>" class="<?=$i===0?'active':''?>"></li>
                <?php endforeach; ?>
              </ol>

              <!-- Slides -->
              <div class="carousel-inner" role="listbox">
                <?php
                $shuffled = $product_list;
                shuffle($shuffled);
                foreach ($shuffled as $i => $p):
                  $active = ($i === 0) ? ' active' : '';
                ?>
                  <div class="item<?=$active?>">
                    <div class="text-center" style="height:420px;background:#f8f9fa;padding:30px;">
                      <img src="<?=base_url($p['image'])?>"
                           alt="<?=htmlspecialchars($p['name'])?>"
                           class="img-responsive center-block carousel-img"
                           style="max-height:280px;object-fit:contain;transition:transform .4s;">
                      <h4 class="mt-3"><?=$p['name']?></h4>
                      <?php if (!empty($p['price'])): ?>
                        <p class="text-success"><strong>₱<?=number_format($p['price'], 2)?></strong></p>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <!-- Controls -->
              <a class="left carousel-control" href="#productCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="right carousel-control" href="#productCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>

            </div><!-- /.carousel -->

          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.col -->
    </div><!-- /.row -->

  </section>
</div>
