/*
 * Copyright (c) 2016 Hot Dang Interactive Media; James Robert Perih
 * Assumes jQuery (for animations)
 */

function prepForm(name, display_name, description, client_price, display_order, id) {
  document.getElementById("name").value = name;
  document.getElementById("display_name").value = display_name;
  document.getElementById("description").value = description;
  document.getElementById("client_price").value = client_price;
  document.getElementById("display_order").value = display_order;
  document.getElementById("service_id").value = id;
}

var submitButton = document.getElementById("submit");

submitButton.addEventListener("click", function(e) {
  submitButton.innerHTML = "Submitting...";
  disableButton(submitButton);

  var name = document.getElementById("name").value;
  var display_name = document.getElementById("display_name").value;
  var description = document.getElementById("description").value;
  var client_price = document.getElementById("client_price").value;
  var display_order = document.getElementById("display_order").value;

  var service_id = document.getElementById("service_id").value;

  var url = "/api/v0/service/type/" + service_id;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", url);
  xmlhttp.setRequestHeader('Content-Type', 'application/json');

  xmlhttp.onreadystatechange = function() {
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      submitButton.innerHTML = "Got it!";
      setTimeout(function() {
        location.reload();
      }, 1000);
    }
  };

  xmlhttp.send(JSON.stringify(
    {
      name: name,
      display_name: display_name,
      description: description,
      client_price: client_price,
      display_order: display_order,
    }
  ));
});

function enableButton(button) {
  button.disabled = false;
  button.style.opacity = 1.0;
  button.style.cursor = "normal";
}

function disableButton(button) {
  button.disabled = true;
  button.style.opacity = 0.6;
  button.style.cursor = "not-allowed";
}
