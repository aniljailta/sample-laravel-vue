<template>
<div class="row">
      <div class="col-lg-3 col-sm-6 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>150</h3>
              <h6>$18000</h6>
              <p>Quotes Generated</p>
            </div>
            <div class="icon">
              <i class="fa fa-list-alt"></i>
            </div>
            
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>53</h3>
              <h6>$20000</h6>
              <p>Invoiced Sales</p>
            </div>
            <div class="icon">
              <i class="fa fa-dollar"></i>
            </div>
            
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>
              <h6>$15000</h6>
              <p>Buildings Ready to Deliver</p>
            </div>
            <div class="icon">
              <i class="fa fa-truck"></i>
            </div>
            
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>
              <h6>$45000</h6>
              <p>Buildings Delivered</p>
            </div>
            <div class="icon">
              <i class="fa fa-check-square-o"></i>
            </div>
           
          </div>
        </div>
        <!-- ./col -->
      </div>


</template>

<style type="text/sass" lang="scss" rel="stylesheet/scss" scoped>
.small-box {
    border-radius: 2px;
    position: relative;
    display: block;
    margin-bottom: 20px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}
.small-box>.inner {
    padding: 10px;
}
.small-box .icon {
    -webkit-transition: all .3s linear;
    -o-transition: all .3s linear;
    transition: all .3s linear;
    position: absolute;
    top: -10px;
    right: 10px;
    z-index: 0;
    font-size: 90px;
    color: rgba(0,0,0,0.15);
}
.small-box h3, .small-box p ,.small-box h6{
    z-index: 5;
    color: #fff !important;
}
.small-box p {
    font-size: 15px;
}
.small-box h6 {
  font-size: 12px !important;
}
.small-box h3 , .small-box h6 {
    font-size: 18px;
    font-weight: bold;
    margin: 0 0 10px 0;
    white-space: nowrap;
    padding: 0;
}
.bg-aqua {
    background-color: #00c0ef !important;
}
.bg-green {
    background-color: #00a65a !important;
}
.bg-yellow {
    background-color: #f39c12 !important;
}
.bg-red {
    background-color: #dd4b39 !important;
}
</style>