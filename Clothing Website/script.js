let email=[],password=[],name=[];
let new_email,new_pass,new_name
let mail=document.getElementById("mail")
let pass=document.getElementById("pass")
let smail=document.getElementById("smail")
let spass=document.getElementById('spass')
let cpass=document.getElementById('cpass')
let fname=document.getElementById("fname")
let mname=document.getElementById("mname")
let lname=document.getElementById("lname")

function signUp(){
  if(fname.value.length==0){
    alert("Enter First Name")
  }
  else if(lname.value.length==0){
    alert("Enter Last Name")
  }
  else if(smail.value.length==0){
    alert("Enter UserName")
  }
  else if(spass.value.length==0){
    alert("Enter Password")
  }
  else if(cpass.value.length==0){
    alert("Enter Confirm Password")
  }
  else if(spass.value!=cpass.value){
    alert("Password and Confirm Password should be same")
  }
  else{
    if (localStorage.getItem('email')==null){
      localStorage.setItem('email','[]')
      localStorage.setItem('password','[]')
      localStorage.setItem('name','[]')
    }

    email=JSON.parse(localStorage.getItem('email'))
    if(email.includes(smail.value))
      {
        alert("Username already exist, please select another username")
       return
  }
    else {
    email.push(smail.value)
    localStorage.setItem('email',JSON.stringify(email))

     password=JSON.parse(localStorage.getItem('password'))
    password.push(spass.value)
    localStorage.setItem('password',JSON.stringify(password))

    nname=fname.value + " " + mname.value + " " + lname.value
    name=JSON.parse(localStorage.getItem('name'))
    name.push(nname)
    localStorage.setItem('name',JSON.stringify(name))
    alert("SignUp Successful")
    window.location.href='login.html'
  }
  }
} 







let position;
function login(){
  if (localStorage.getItem('email')==null){
    localStorage.setItem('email','[]')
    localStorage.setItem('password','[]')
    localStorage.setItem('name','[]')
  }
  email=JSON.parse(localStorage.getItem('email'))
  name=JSON.parse(localStorage.getItem('name'))
  password=JSON.parse(localStorage.getItem('password'))
  if (mail.value.length==0){
    alert("please enter Username")
  }
  else if(pass.value.length==0){
    alert("Please Enter Password")
  }
  else{
    if (email.includes(mail.value) || email==null){
      position=email.indexOf(mail.value)
      if (pass.value!=password[position]){
        alert("You have entered wrong password")
      }
      else{
        alert(`Login Successful,${name[position]}`)
        localStorage.setItem("logname",JSON.stringify(name[position]))
        window.location.href='home.html'
      }
    }
    else{
      alert("Your UserName is not registered, Please SignUp if You are new to the website")

    }
  }

}

