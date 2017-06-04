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
      label {
        text-align: left !important;
        color: #ffffff;
      }
      .form-control {
        color: #ffffff !important;
        background-color: #030303;
      }

      h3 {
        color: #fff;
      }
    </style>
</head>

<body id="page-top" class="index">

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
      <h2>Service Provider: <?php echo($provider->name); ?></h2>
    </div>
  </header>

  <div class="panel panel-default" style="background-color: #63666a; width: 90% !important; margin: 16px auto; padding: 16px; display: flex; flex-direction: row; justify-content: space-around; flex-wrap: wrap">
    <div style="width: 50%;">
      <h3>Edit Details</h3>
      <form action="/api/v0/providers/<?php echo($provider->uuid); ?>/edit" method="post" id="new_form">
          <div class="form-group">
              <input type="hidden" id="uuid" name="uuid" value="<?php echo($provider->uuid)" />
              <div class="form-group">
                <label for="name">Provider Company:</label><input value="<?php echo($provider->name); ?>" class="form-control" type="text" id="name" name="name" placeholder="Provider Name"></input>
              </div>

              <div class="form-group">
                <label for="billing_name">Contact Name:</label><input value="<?php echo($provider->billing_name); ?>" class="form-control" type="text" id="billing_name" name="billing_name" placeholder="Billing Name">
              </div>

              <div class="form-group">
                <label for="email">Provider Email:</label><input value="<?php echo($provider->email); ?>" class="form-control" type="email" id="email" name="email" placeholder="Provider Email">
              </div>

              <div class="form-group">
                <label for="phone">Provider Phone:</label><input value="<?php echo($provider->phone); ?>" class="form-control" type="phone" id="phone" name="phone" placeholder="204-000-0000">
              </div>

              <!-- TODO: onBlur, re-focus the map picker -->
              <div class="form-group">
                <label for="address">Provider Address:</label><input value="<?php echo($provider->address); ?>" class="form-control" type="text" id="address" name="address" placeholder="Provider Address">
              </div>

              <!-- replace with map picker -->
              <!-- <div id="mapPicker" style="margin: 0 auto; width: 75%; height: 350px;"></div> -->

              <div class="form-group">
                <label for="lat">Lat:</label><input value="<?php echo($provider->lat); ?>" class="form-control" type="text" id="lat" name="lat" placeholder="Latitude"/>
                <label for="lng">Lng:</label><input value="<?php echo($provider->lng); ?>" class="form-control" type="text" id="lng" name="lng" placeholder="Longitude"/>
                <label for="radius">Radius:</label><input value="<?php echo($provider->radius); ?>" class="form-control" type="number" id="radius" name="radius" placeholder="250m default radius"/>
              </div>

              <div class="form-group">
                <label for="tow">Base Tow Rate:</label><input value="<?php echo($provider->rateTow); ?>" class="form-control" type="number" id="tow" name="tow" value="0" placeholder=""/>
                <label for="lockout">Vehicle Lockout Rate:</label><input value="<?php echo($provider->rateLockout); ?>" class="form-control" type="number" id="lockout" name="lockout" value="0" placeholder=""/>
                <label for="tire">Tire Change Rate:</label><input value="<?php echo($provider->rateTire); ?>" class="form-control" type="number" id="tire" name="tire" value="0" placeholder=""/>
                <label for="fuel">Fuel Rate:</label><input value="<?php echo($provider->rateFuel); ?>" class="form-control" type="number" id="fuel" name="fuel" value="0" placeholder=""/>
                <label for="boost">Boost Rate:</label><input value="<?php echo($provider->rateBoost); ?>" class="form-control" type="number" id="boost" name="boost" value="0" placeholder=""/>
              </div>

              <div class="form-group">
                <button type="button" style="width: 100%;" class="btn btn-primary" id="submit">Edit</button>
              </div>
          </div>
      </form>
    </div>

    <div width="100%">
      <h3>Order History</h3>
      <p>Coming soon!</p>
    </div>
  </div>
  <!-- jQuery -->
  <script src="/vendor/jquery/jquery.min.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

  <!-- Contact Form JavaScript -->
  <script src="/js/editProviderDetails.js"></script>

  <!-- Theme JavaScript -->
  <script src="/js/freelancer.min.js"></script>

  <!-- Retina Images -->
  <script type="text/javascript" src="/js/retina.min.js"></script>

  <!-- Inline scripts -->
  <script>
  </script>
</body>

</html>
