<h2>Hey, <?php echo $provider->name; ?>!</h2>
<p>There's a new service request available in your area! Here's the deets:</p>

<p><strong>Service Type:</strong> <?php echo($order->service_type); ?></p>
<p><strong>Customer Car Description:</strong> <?php echo $order->car_description ?></p>
<p><strong>Origin:</strong> <?php echo $order->origin_label; ?></p>

<?php
  if (strcmp($order->service_type,'tow') === 0) {
    // it is a Tow
    ?>
    <p>Destination:</p>
    <p><?php echo $order->destination_label; ?></em></p>

    <p>Tow Distance:</p>
    <p><?php echo $order->tow_distance / 1000; ?> km</em></p>

    <p>Needs Winch:</p>
    <p><?php echo $order->needs_winch ? '<strong>YES</strong>' : '<small>No</small>'; ?></em></p>

    <p>Needs Flatbed:</p>
    <p><?php echo $order->needs_flatbed ? '<strong>YES</strong>' : '<small>No</small>'; ?></em></p>

    <?php
  }
?>

<p><strong>Customer's First Name:</strong> <?php echo($order->firstname) ?></p>
<p><strong>Customer's Phone Number:</strong> <?php echo($order->phone) ?></p>
<p><strong>Paid: </strong></p>
<p><?php echo ($order->isPaid ? 'Yes' : 'No'); ?></em></p>

<?php
  $lookup = "";
  $extra = 0;

  // these are provider prices over base
  // base is per client
  $fuelExtra = 5.0; // 5 extra, or $50.00
  $tireExtra = 10.0 * 0.85; // 10 extra, or $55.00
  $towExtra = 25.0 * 0.85; // 25 extra, or $70.00
  $towToolsExtra = 15.00 * 0.85; // 15 extra, or 45 + 25 + 15 = $85.00
  $goaRate = 35.00;
  $kmOverage = 10.0; // when per-km charge kicks in
  $kmExtra = 1.50 * 0.85; // km overage rate


  switch ($order->service_type) {
    case "tow":
      $lookup = "rateTow";
      $extra += $towExtra; // base
      // plus if winching
      if ($order->needs_winch) {
        $extra += $towToolsExtra;
      } else if ($order->needs_flatbed) {
        $extra += $towToolsExtra;
      }

      // plus kms > 15km
      // it is in meters
      if ($order->tow_distance > $kmOverage * 1000) {
        $differenceInKm = $order->tow_distance - ($kmOverage * 1000);
        $extra += (($differenceInKm / 1000) * $kmExtra);
      }
      break;
    case "jump":
      $lookup = "rateBoost";
      break;
    case "tire":
      $lookup = "rateTire";
      $extra = $tireExtra;
      break;
    case "fuel";
      $lookup = "rateFuel";
      $extra += $fuelExtra;
      break;
    case "lockout";
      $lookup = "rateLockout";
      break;
  }
  // determine earning potential for this provider
  $earningPotential = $provider->$lookup + $extra;
  $earningPotential = number_format($earningPotential, 2);
?>

<p><big><strong>This Job Pays:</strong></big> $<?php echo($earningPotential); ?></p>

<p>To take the service request, and learn more details, <a href="https://api.boostbuddy.ca/admin/orders/<?php echo($order->order_number); ?>/info/<?php echo($provider->uuid); ?>">Click here</a></p>

<p><strong>Hey:</strong> if the above link does not work, you could try clicking on this url:<br/>
    https://api.boostbuddy.ca/admin/orders/<?php echo($order->order_number); ?>/info/<?php echo($provider->uuid); ?>
</p>

<small>This email was sent to <?php echo ($provider->name); ?> on behalf of Boostbuddy.
