<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="winnipeg,boost,stranded,winter,car,truck,van,road,side,assistance">
    <meta name="author" content="Hot Dang Interactive">
    <title>Boostbuddy - Winnipeg, MB</title>

    <!-- Bootstrap Core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="/css/freelancer.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">

    <!-- DataTables -->
    <link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
      input {
        color: #000000 !important;
      }
    </style>
</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
        <div class="container">
            <div class="navbar-header page-scroll">
                <img src="/img/logo.png" data-rjs="3" />
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header>
      <div class="container container-main container-fluid">
        <h2>All Service Requests</h2>
        <div class="table-responsive" style="background-color: #ffffff; color: #000000; padding-left: 16px; padding-top: 16px; padding-bottom: 16px;">
          <table id="active_orders" class="table table-condensed">
            <thead>
              <tr>
                <td>Order Number</td>
                <td>Quoted/Captured</td>
                <td>Paid</td>
                <td>Status</td>
                <td>Type</td>
                <td>Customer</td>
                <td>Phone</td>
                <td>Assigned</td>
                <td>Date Added</td>
                <td>Actions</td>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($orders as $order) {
                ?>
                <tr>
                  <td><a href="/admin/orders/<?php echo($order->order_number); ?>/info/admin"><?php echo $order->order_number; ?></a></td>
                  <td><?php echo ( '$' . number_format($order->quoted_price, 2) . ' / $' . number_format($order->amountPaid, 2) ); ?></td>
                  <td><?php echo ($order->isPaid ? 'Yes' : 'No'); ?></td>
                  <td><?php echo ($order->status); ?></td>
                  <td><?php echo ($order->service_type); ?></td>
                  <td><?php echo ($order->firstname . " " . $order->lastname) ?></td>
                  <td><?php echo ($order->phone) ?></td>
                  <td><?php echo ($order->service_provider); ?></td>
                  <td><?php echo ($order->updated_at) ?></td>
                  <td>
                    <button class="btn btn-danger btn-xs">Cancel</button>
                    <button class="btn btn-warning btn-xs">Reassign</button>
                  </td>
                </tr>
                <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </header>

    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-below boostbuddy-base">
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-6 boostbuddy-copyright">
                      Copyright © 2016 BoostBuddy All rights reserved
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="/vendor/jquery/jquery.min.js"></script>

    <!-- Datatables -->
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function(){
        $('#active_orders').DataTable();
      });
    </script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="/js/submitForm.js"></script>

    <!-- Theme JavaScript -->
    <script src="/js/freelancer.min.js"></script>

    <!-- Retina Images -->
    <script type="text/javascript" src="/js/retina.min.js"></script>
</body>

</html>
