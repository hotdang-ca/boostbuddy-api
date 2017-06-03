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

  switch ($order->service_type) {
    case "tow":
      $lookup = "rateTow";
      $extra += 30; // base
      // plus if winching
      if ($order->needs_winch) {
        $extra += 20;
      } else if ($order->needs_flatbed) {
        $extra += 20;
      }

      // plus kms > 15km
      // it is in meters
      if ($order->tow_distance > 15000) {
        $differenceInKm = $order->tow_distance - 15000;
        $extra += (($differenceInKm / 1000) * 2.5);
      }
      break;
    case "jump":
      $lookup = "rateBoost";
      break;
    case "tire":
      $lookup = "rateTire";
      break;
    case "fuel";
      $lookup = "rateFuel";
      $extra += 10;
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
