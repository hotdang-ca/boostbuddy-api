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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

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
        <p><?php echo $provider->name; ?></p>
        <h2>Service Request<br/>Details</h2>

        <!-- TODO: if status is available OR taken by me; otherwise say it's not available. -->
        <div class="boostbuddy-broadcast">
          <div style="margin: 0 auto;">
            <dl class="dl-horizontal">
              <dt><p class="small">Order Number:</p></dt>
              <dd><p><?php echo $order->order_number; ?></p></dd>

              <dt><p class="small"><strong>Paid: </strong></p></dt>
              <dd><p><?php echo ($order->isPaid ? 'Yes' : 'No'); ?></em></p></dd>

              <dt><p class="small">Status:</p></dt>
              <dd><p><?php echo $order->status; ?></p></dd>

              <dt><p class="small">Service Type:</p></dt>
              <dd><p><?php echo $order->service_type; ?></em></p></dd>

              <dt><p class="small">Origin:</p></dt>
              <dd><p><?php echo $order->origin_label; ?></em></p></dd>

              <?php
                if (strcmp($order->service_type,'tow') === 0) {
                  // it is a Tow
                  ?>

                  <dt><p class="small">Tow Destination:</p></dt>
                  <dd><p><?php echo $order->destination_label; ?></em></p></dd>

                  <dt><p class="small">Tow Distance:</p></dt>
                  <dd><p><?php echo $order->tow_distance / 1000; ?> km</em></p></dd>

                  <dt><p class="small">Needs Winch:</p></dt>
                  <dd><p><?php echo $order->needs_winch ? '<strong>YES</strong>' : '<small>No</small>'; ?></em></p></dd>

                  <dt><p class="small">Needs Flatbed:</p></dt>
                  <dd><p><?php echo $order->needs_flatbed ? '<strong>YES</strong>' : '<small>No</small>'; ?></em></p></dd>

                  <?php
                }
              ?>

              <tr>
                <dt><p class="small">Customer Name:</p></dt>
                <dd><p><?php echo $order->firstname ?></em></p></dd>
              </tr>

              <tr>
                <dt><p class="small">Customer Number:</p></dt>
                <dd><p>
                  <?php
                  if ( $order->service_provider == $provider->name || !isset($order->service_provider) ) {
                    echo $order->phone;
                  } else {
                    echo '<em>Omitted</em>';
                  }
                  ?></p></dd>
              </tr>

              <tr>
                <dt><p class="small">Customer Car Description:</p></dt>
                <dd><p><?php echo $order->car_description ?></em></p></dd>
              </tr>

              <tr>
                <dt><p class="small"><big>This Job Pays:</big></p></dt>
                <dd><p><big>$<?php echo $order->earningPotential ?></em></big></p></dd>
              </tr>

            </dl>
          </div>
        </div>
        <?php
          if ($provider->name !== 'admin') {
            if ($order->status_code < 3) { // if it's pending, paid, or announced
              ?>
              <div class="row">
                <h4 id="acknowledgementText">* By accepting the job, <?php echo $provider->name; ?>, you are responsible<br/>and must complete task</h4>
                <input style="display: block; height: 64px; width: 80%; margin: 0 auto; background-color: #2b2b2b; font-size: 24px; text-align: center; font-family: Roboto" id="eta" name="eta" type="number" placeholder="ETA in minutes (eg, 30)" /><br/>
                <input type="hidden" id="uuid" value="<?php echo $provider->uuid; ?>" />
                <input type="hidden" id="order" value="<?php echo $order->order_number; ?>" />
                <button onClick="acceptJob();" id="submitButton" class="boostbuddy-button" style="width: 80%; opacity: 0.5" disabled>I understand.<br>Accept the job</button>
              </div>
              <?php
            } else if ($order->service_provider == $provider->name) {
              // not pending/paid/announced and we took it
              ?>
                <div class="row">
                  <h4>This job is your responsibility now.</h4>
                  <button onClick="updateStatus('complete');" id="finishedButton" class="boostbuddy-button" style="width: 80%;">Mark Job Complete</button> <br><br>
                  <button onClick="updateStatus('goa');" id="goaButton" class="boostbuddy-button" style="width: 60%; opacity: 0.80"><small>Mark Job GOA</small></button><br><br>
                  <button onClick="updateStatus('cancel');" id="failButton" class="boostbuddy-button" style="width: 60%; opacity: 0.80"><small>Relinquish Service Request</small></button>
                </div>
              <?php
            } else {
              // someone else took it
              ?>
                <div class="row">
                <h4>This job is no longer available.</h4>
                <p>The other service provider may relinquish the job... you could try <a href="">refreshing the page</a></p>
                </div>
              <?php
            }
          }
          // else, show nothing. Eg, we are ADMIN.
        ?>
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

    <!-- Bootstrap Core JavaScript -->
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <script src="/js/acceptJob.js"></script>

    <!-- Theme JavaScript -->
    <script src="/js/freelancer.min.js"></script>

    <!-- Retina Images -->
    <script type="text/javascript" src="/js/retina.min.js"></script>
</body>

</html>
