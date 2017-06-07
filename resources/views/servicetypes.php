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
        background-color: #030303;
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
        <h2>All Service Types</h2>
        <div class="table-responsive" style="background-color: #ffffff; color: #000000; padding-left: 16px; padding-top: 16px; padding-bottom: 16px;">
          <table id="service_types" class="table table-condensed">
            <thead>
              <tr>
                <td>Icon</td>
                <td>Short name</td>
                <td>Display Name</td>
                <td>Description</td>
                <td>Client Price</td>
                <td>Display Order</td>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($types as $type) {
              ?>
                <tr>
                  <td><img src="https://boostbuddy.ca<?php echo($type->icon_url); ?>" width="64" height="64" /></td>
                  <td><a onClick="prepForm('<?php echo $type->name; ?>', '<?php echo $type->display_name; ?>', '<?php echo $type->description; ?>', '<?php echo $type->client_price; ?>', '<?php echo $type->display_order; ?>', '<?php echo $type->id; ?>');" data-toggle="modal" data-target="#dialog-editservice"><?php echo($type->name . " / " . $type->id ); ?></a></td>
                  <td><?php echo ($type->display_name); ?></td>
                  <td><?php echo ($type->description); ?></td>
                  <td><?php echo ('$' . number_format($type->client_price, 2)); ?></td>
                  <td><?php echo ($type->display_order) ?></td>
                </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </header>


    <!-- Modals -->
    <!-- Edit Service -->
    <div class="modal fade" id="dialog-editservice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title" id="modal-title">Edit Service Type</h3>
          </div>
          <div class="modal-body">
            <form action="/api/v0/service/types" method="post" id="new_form">
                <div class="form-group">
                    <input type="hidden" id="service_id" name="service_id" value="" />

                    <div class="form-group">
                      <label for="name">Type Name:</label><input class="form-control" type="text" id="name" name="name" placeholder="Service Name"></input>
                    </div>

                    <div class="form-group">
                      <label for="display_name">Display Name:</label><input class="form-control" type="text" id="display_name" name="display_name" placeholder="Display Name">
                    </div>

                    <div class="form-group">
                      <label for="description">Description:</label><textarea class="form-control" id="description" name="description" placeholder="Description"></textarea>
                    </div>

                    <div class="form-group">
                      <label for="client_price">Client Price:</label><input class="form-control" type="number" id="client_price" name="client_price" placeholder="0.00">
                    </div>

                    <div class="form-group">
                      <label for="client_price">Display Order:</label><input class="form-control" type="number" id="display_order" name="display_order" placeholder="0">
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="submit">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /Modals -->

    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-below boostbuddy-base">
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-6 boostbuddy-copyright">
                      Copyright © 2016, 2017 BoostBuddy All rights reserved
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
        $('#service_types').DataTable();
      });
    </script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Form Handler -->
    <script src="/js/editService.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="/js/freelancer.min.js"></script>

    <!-- Retina Images -->
    <script type="text/javascript" src="/js/retina.min.js"></script>
</body>

</html>
