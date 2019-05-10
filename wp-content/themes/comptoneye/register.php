<?php /* Template Name: Register-template */ 
get_header();
?>
<?php
//////////////////////////////////////////////////
//print_r($_POST);

function sendMessages($data,$target){
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAxOdsypE:APA91bHriZpLzpSHI_Okba4xw7ybqI74WubYqtcgOwue4dyOx3lXMTKUqVl4T-o8M3qTsr1oUlytD7PgBR0P-SC3XK6iQNH4rRcROVXQn5jp8iJcNIJ0Zl7tZquLZoaQ4-T_pmBDyL0e';
                    
        $fields = array();
        $notification= array('notification' =>json_encode($data));
        // $fields['data'] = $notification;
        $fields['data'] = $data;
        if(is_array($target)){
            $fields['registration_ids'] = $target;
        }else{
            $fields['to'] = $target;
        }
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
          'Authorization:key='.$server_key
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //echo $result;
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        //return $result;
}

if(isset($_POST['form_submit'])){
  global $wpdb;
  $table="ce_user_info";
  $regno =  sanitize_text_field($_POST['registno']);
  $lname =  sanitize_text_field($_POST['lname']);
  $fname =  sanitize_text_field($_POST['fname']);
  

  $wpdb->insert($table,array(
    'registno'=>$regno,
    'lastname'=>$lname,
    'firstname'=>$fname
    ),array('%s','%s','%s'));

$token = "clYR9qjzOPg:APA91bEZUDLgdHttI39guJcuIXLH-ij9go06xUwrSTYUduWQTUHqZRsuzLxsUL4qtIkQ7AushflS3km8Pm86BqiogV2CATx6STRMSKBG2oz2q1Gx6ebShJllXV3TmvuEEM1AlTjGNWst"; 
        sendMessages($data , $token);  
  if($wpdb)
  {
    echo '<h3 style="text-align:center">Thank You, We will contact you shortly.</h3>';
  }
}
?>


<style type="text/css">
  #success_message{ display: none;}
  .mycolor{
    color: #0c3374;
    margin-bottom: 7px;
    line-height: 20px;
  }
  .mydiv{
    margin-bottom: 45px;
  }
</style>
<<!-- script>
  
function notifyMe() {
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }

  // Let's check if the user is okay to get some notification
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
  var options = {
        body: "Successfully saved your information. We will Contact you shortly.",
              icon: "logo.jpg",
              dir : "ltr"
    };
  var notification = new Notification("well done!!",options);
  }

  // Otherwise, we need to ask the user for permission
  // Note, Chrome does not implement the permission static property
  // So we have to check for NOT 'denied' instead of 'default'
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      // Whatever the user answers, we make sure we store the information
      if (!('permission' in Notification)) {
        Notification.permission = permission;
      }

      // If the user is okay, let's create a notification
      if (permission === "granted") {
        var options = {
              body: "Successfully saved your information. We will Contact you shortly.",
              icon: "logo.jpg",
              dir : "ltr"
          };
        var notification = new Notification("WEll DONE",options);
      }
    });
  }

  // At last, if the user already denied any notification, and you
  // want to be respectful there is no need to bother them any more.
}

</script> -->


<body style="background-color:white; ">



<!-- new form  -->
    <div class="container">

    <form class="well " action="" method="post"  id="" style="background-color: wheat; margin-top: 15px;">
<fieldset>

<!-- Form Name -->
<div style="border-bottom: 3px solid #34fadc; margin-bottom: 5px; width: 500px; color: #0c3372; text-transform: uppercase;">
<center><h2><b><i class="fa fa-address-card" aria-hidden="true"></i> Registration Form</b></h2></center>
</div><br>
<!-- Text input-->
<h1 style="color: red;" id="bigOne"></h1>


<div class="form-group col-md-4 col-lg-4 mydiv">
  <label class="control-label mycolor">Registration Number</label>  
  <div class="inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-send"></i></span>
  <input placeholder="Registration no." class="form-control" name="registno" type="text" required>
    </div>
  </div>
</div>

<!-- Text input-->

<div class="form-group col-md-4 col-lg-4 mydiv">
  <label class="control-label mycolor" >Last Name</label> 
    <div class="inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input name="lname" placeholder="Last Name" class="form-control"  type="text" required>
    </div>
  </div>
</div>

<div class="form-group col-md-4 col-lg-4 mydiv">
  <label class=" control-label mycolor" >First Name</label> 
    <div class=" inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input name="fname" placeholder="First Name" class="form-control"  type="text" required>
    </div>
  </div>
</div>



<!-- Select Basic -->

<!-- Success message -->
<div class="alert alert-success" role="alert" id="success_message">Success <i class="glyphicon glyphicon-thumbs-up"></i> Success!.</div>

<!-- Button -->
  <div class="col-md-4"><br>
    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<button type="submit" name="form_submit" class="btn btn-warning" >&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSUBMIT <span class="glyphicon glyphicon-send"></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</button>
  </div>


</fieldset>
</form>
</div>
    </div><!-- /.container -->

<script src="https://www.gstatic.com/firebasejs/4.9.0/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyAiwrKX06j4HWH8sz1bo_50JkBpgeiav6w",
    authDomain: "myproject-f233c.firebaseapp.com",
    databaseURL: "https://myproject-f233c.firebaseio.com",
    projectId: "myproject-f233c",
    storageBucket: "myproject-f233c.appspot.com",
    messagingSenderId: "845696256657"
  };
  firebase.initializeApp(config);

  var bigOne = document.getElementById('bigOne');
  var dbRef = firebase.database().ref().child('records');
  dbRef.on('value',snap => bigOne.innerText = snap.val());
</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>



