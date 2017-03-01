<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="winnipeg,boost,stranded,winter,car,truck,van,road,side,assistance">
    <meta name="author" content="Hot Dang Interactive">
    <title>Providers Admin - Boostbuddy - Winnipeg, MB</title>

    <!-- Bootstrap Core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="/css/freelancer.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">

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

      .form-control {
        color: #ffffff !important;
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
        <h2>All Service Providers</h2>
        <button type="button" onClick="prep_map();" class="boostbuddy-button" data-toggle="modal" data-target="#dialog-newprovider">
          New Service Provider
        </button>

        <div class="table-responsive" style="background-color: #ffffff; color: #000000; padding-left: 16px; padding-top: 16px; padding-bottom: 16px;">
          <table id="providers" class="table table-condensed">
            <thead>
              <tr>
                <td>UUID</td>
                <td>Name</td>
                <td>Contact</td>
                <td>Address</td>
                <td>Phone</td>
                <td>Email</td>
                <td>Actions</td>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($providers as $provider) {
                ?>
                <tr>
                  <td><a href="/admin/providers/<?php echo($provider->id); ?>/info"><?php echo $provider->uuid; ?></a></td>
                  <td><?php echo $provider->name; ?></td>
                  <td><?php echo $provider->billing_name; ?></td>
                  <td><?php echo $provider->address; ?></td>
                  <td><?php echo $provider->phone; ?></td>
                  <td><?php echo $provider->email; ?></td>
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
                      Copyright © 2017 BoostBuddy All rights reserved
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modals -->
    <!-- New Provider -->
    <div class="modal fade" id="dialog-newprovider" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title" id="modal-title">New Service Provider</h3>
          </div>
          <div class="modal-body">
            <form action="/api/v0/providers/add" method="post" id="new_form">
                <div class="form-group">
                    <div class="form-group">
                      <label for="name">Provider Company:</label><input class="form-control" type="text" id="name" name="name" placeholder="Provider Name"></input>
                    </div>

                    <div class="form-group">
                      <label for="billing_name">Contact Name:</label><input class="form-control" type="text" id="billing_name" name="billing_name" placeholder="Billing Name">
                    </div>

                    <div class="form-group">
                      <label for="email">Provider Email:</label><input class="form-control" type="email" id="email" name="email" placeholder="Provider Email">
                    </div>

                    <div class="form-group">
                      <label for="phone">Provider Phone:</label><input class="form-control" type="phone" id="phone" name="phone" placeholder="204-000-0000">
                    </div>

                    <!-- TODO: onBlur, re-focus the map picker -->
                    <div class="form-group">
                      <label for="address">Provider Address:</label><input class="form-control" type="text" id="address" name="address" placeholder="Provider Address">
                    </div>

                    <!-- replace with map picker -->
                    <div id="mapPicker" style="margin: 0 auto; width: 75%; height: 350px;"></div>

                    <div class="form-group">
                      <label for="lat">Lat:</label><input class="form-control" type="text" id="lat" name="lat" placeholder="Latitude"/>
                      <label for="lng">Lng:</label><input class="form-control" type="text" id="lng" name="lng" placeholder="Longitude"/>
                      <label for="radius">Radius:</label><input class="form-control" type="number" id="radius" name="radius" placeholder="250m default radius"/>
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="submit">Add</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /Modals -->

    <!-- jQuery -->
    <script src="/vendor/jquery/jquery.min.js"></script>

    <!-- DataTables -->
    <link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

    <!-- Map Picker -->
    <script src="//maps.google.com/maps/api/js?key=***REMOVED***&libraries=places"></script>
    <script src="/js/locationpicker.jquery.js"></script>

    <!-- Datatables -->
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function(){
        $('#providers').DataTable();
      });
    </script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="/js/submitNewProviderForm.js"></script>

    <!-- Theme JavaScript -->
    <script src="/js/freelancer.min.js"></script>

    <!-- Retina Images -->
    <script type="text/javascript" src="/js/retina.min.js"></script>

    <script>
    function prep_map() {
      // pre-prep the map, because it takes a while
      $('#mapPicker').locationpicker({
        radius: 20000,
        zoom: 10,
        location: {latitude: 49.91016582206002, longitude: -97.10714169311524},
        inputBinding: {
          latitudeInput: $('#lat'),
          longitudeInput: $('#lng'),
          locationNameInput: $('#address'),
          radiusInput: $('#radius')
        }
      });

      setTimeout(function() {
        $('#mapPicker').locationpicker('autosize');
      }, 1000);
    }
    </script>
</body>

</html>
