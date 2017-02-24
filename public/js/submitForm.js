/*
 * Copyright (c) 2016 Hot Dang Interactive Media; James Robert Perih
 * Assumes jQuery (for animations)
 */

var submitButton = document.getElementById("submitButton");
hide("completedForm");

submitButton.addEventListener("click", function() {

  submitButton.innerHTML = "Submitting...";
  disableButton(submitButton);

  var name = document.getElementById("fullname").value;
  var email = document.getElementById("email").value;
  var useragent = navigator.userAgent;

  if (validateEmail(email) && validateName(name)) {
    var params = "name=" + name + "&email=" + email + "&useragent=" + useragent;
    makePost(params, "mail/contact.php");
    document.getElementById("formName").innerHTML = name;
  } else {
    alert("Bad email or name");
    submitButton.innerHTML = "Try again";
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
