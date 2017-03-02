var element = document.getElementById('eta');
var button = document.getElementById('submitButton');
var uuid = document.getElementById('uuid').value;
var order = document.getElementById('order').value;
var acknowledgementText = document.getElementById('acknowledgementText');

element.addEventListener('input', function() {
  if (element.value === undefined || element.value === '') {
    button.disabled = true;
    button.style.opacity = '0.5';
  } else {
    button.disabled = false;
    button.style.opacity = '1.0';
  }
}, false);

function updateStatus(statusType) {
  if (statusType === 'goa') {
    console.log('marking Gone On Arrival');
  } else if (statusType === 'complete') {
    console.log('marking Finished');
  } else if (statusType === 'cancel') {
    console.log('marking Cancelled.');
  }
}

function acceptJob() {
  // get estimate
  var eta = element.value;

  button.innerHTML = 'Accepting...';
  button.disabled = true;
  button.style.opacity = '0.5';

  // xhr
  var url = '/api/v0/service/request/' + order + '/take';
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", url);
  xmlhttp.setRequestHeader('Content-Type', 'application/json');

  xmlhttp.onreadystatechange = function() {
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      var orderStatus = JSON.parse(xmlhttp.responseText);

      if (orderStatus.status_code === 3) {
        // we got it!
      }

      setTimeout(function() {
        location.reload();
      }, 1000);
    }
  };

  xmlhttp.send(JSON.stringify(
    {
      provider: uuid,
      eta: eta,
    }
  ));
}
