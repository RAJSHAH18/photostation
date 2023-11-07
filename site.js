function loginSubmit(event){
  loginemail = document.getElementById("email").value;
  loginpassword = document.getElementById("password").value;

if(loginemail == '' || loginpassword == ''){
    alert("All fields are mandatory!");
    event.preventDefault();
  }
}


function registerSubmit(event){
  registeremail = document.getElementById("email").value;
  registerpassword = document.getElementById("password").value;
  registerusername = document.getElementById("username").value;
  registeraddress = document.getElementById("address").value;
  registerphone = document.getElementById("phone").value;

if(registerusername == '' || registeraddress == '' || registerphone == '' || registeremail == '' || registerpassword == ''){
    alert("All fields are mandatory!");
    event.preventDefault();
  }

}

function paymentSubmit(event){
  creditCard = document.getElementById("cardnumber").value;
  cardname = document.getElementById("cardname").value;
  cvc = document.getElementById("cvc").value;
  type = document.getElementById("type").value;

  if(creditCard == '' || cardname == '' || cvc == '' || type == ''){
    alert("All fields are mandatory!");
    event.preventDefault();
  }

  if(creditCard.length > 16){
    alert("Credit card isn't valid!");
    event.preventDefault();
  }
  
}