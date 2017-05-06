<h2>Hey, Admin!</h2>
<p>There's a new service request entered. Here are the deets:</p>

<p><strong>Customer's First Name:</strong> <?php echo($order->firstname) ?></p>
<p><strong>Customer's Phone Number:</strong> <?php echo($order->phone) ?></p>
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
<p>To monitor this and other jobs in the queue, <a href="https://api.boostbuddy.ca/admin/orders/">Click here</a></p>
<small>This email was sent to the Admin Team on behalf of Boostbuddy.</small>
