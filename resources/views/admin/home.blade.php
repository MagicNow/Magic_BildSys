@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Dashboard
            <small>Painel de controle</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active"><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        </ol>
    </section>
    <div class="content">
        <div class="row">


        </div>
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>150</h3>

                        <p>New Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>53<sup style="font-size: 20px">%</sup></h3>

                        <p>Bounce Rate</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>44</h3>

                        <p>User Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>65</h3>

                        <p>Unique Visitors</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">CPU Traffic</span>
                        <span class="info-box-number">90<small>%</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Likes</span>
                        <span class="info-box-number">41,410</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sales</span>
                        <span class="info-box-number">760</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Members</span>
                        <span class="info-box-number">2,000</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recently Added Products</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <li class="item">
                                <div class="product-img">
                                    <img src="http://placehold.it/50x50" alt="Product Image">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">Samsung TV
                                        <span class="label label-warning pull-right">$1800</span></a>
                        <span class="product-description">
                          Samsung 32" 1080p 60Hz LED Smart HDTV.
                        </span>
                                </div>
                            </li>
                            <!-- /.item -->
                            <li class="item">
                                <div class="product-img">
                                    <img src="http://placehold.it/50x50" alt="Product Image">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">Bicycle
                                        <span class="label label-info pull-right">$700</span></a>
                        <span class="product-description">
                          26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                        </span>
                                </div>
                            </li>
                            <!-- /.item -->
                            <li class="item">
                                <div class="product-img">
                                    <img src="http://placehold.it/50x50" alt="Product Image">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">Xbox One <span class="label label-danger pull-right">$350</span></a>
                        <span class="product-description">
                          Xbox One Console Bundle with Halo Master Chief Collection.
                        </span>
                                </div>
                            </li>
                            <!-- /.item -->
                            <li class="item">
                                <div class="product-img">
                                    <img src="http://placehold.it/50x50" alt="Product Image">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">PlayStation 4
                                        <span class="label label-success pull-right">$399</span></a>
                        <span class="product-description">
                          PlayStation 4 500GB Console (PS4)
                        </span>
                                </div>
                            </li>
                            <!-- /.item -->
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="javascript:void(0)" class="uppercase">View All Products</a>
                    </div>
                    <!-- /.box-footer -->
                </div>
            </div>
            <div class="col-md-4">
                <!-- Info Boxes Style 2 -->
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Inventory</span>
                        <span class="info-box-number">5,200</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>
                  <span class="progress-description">
                    50% Increase in 30 Days
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Mentions</span>
                        <span class="info-box-number">92,050</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 20%"></div>
                        </div>
                  <span class="progress-description">
                    20% Increase in 30 Days
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Downloads</span>
                        <span class="info-box-number">114,381</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 70%"></div>
                        </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Direct Messages</span>
                        <span class="info-box-number">163,921</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 40%"></div>
                        </div>
                  <span class="progress-description">
                    40% Increase in 30 Days
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </div>
    </div>
@endsection
