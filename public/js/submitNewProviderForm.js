/*
 * Copyright (c) 2016 Hot Dang Interactive Media; James Robert Perih
 * Assumes jQuery (for animations)
 */

var submitButton = document.getElementById("submit");

submitButton.addEventListener("click", function(e) {

  submitButton.innerHTML = "Submitting...";
  disableButton(submitButton);

  var name = document.getElementById("name").value;
  var billingname = document.getElementById("billing_name").value;
  var email = document.getElementById("email").value;
  var phone = document.getElementById("phone").value;
  var address = document.getElementById("address").value;
  var lat = document.getElementById("lat").value;
  var lng = document.getElementById("lng").value;
  var radius = document.getElementById("radius").value;
  var boost = document.getElementById("boost").value;
  var tow = document.getElementById("tow").value;
  var lockout = document.getElementById("lockout").value;
  var fuel = document.getElementById("fuel").value;
  var tire = document.getElementById("tire").value;

  if (validateEmail(email) && validateName(name)) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "/api/v0/providers/add");
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
        billing_name: billingname,
        email: email,
        phone: phone,
        address: address,
        lat: lat,
        lng: lng,
        radius: radius,
        boost: boost,
        fuel: fuel,
        lockout: lockout,
        tire: tire,
        tow: tow,
      }
    ));
  } else {
    enableButton(submitButton);
  }
});


function makePost(params, serverUrl) {
  var http = new XMLHttpRequest();
  http.open("POST", serverUrl, true);

  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  http.onreadystatechange = function() {
    if(http.readyState == 4 && http.status == 200) {
      submitButton.innerHTML = "Got it!";
      hide("form");
      show("completedForm");
    } else {
      // some other state
      enableButton(submitButton);
    }
  };

  http.send(params);

}
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validateName(name) {
  return name.length > 3;
}

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

function show(hiddenElementId) {
  $("#" + hiddenElementId).fadeIn("slow");
}

function hide(visibleElementId) {
  $("#" + visibleElementId).fadeOut("slow");
}
