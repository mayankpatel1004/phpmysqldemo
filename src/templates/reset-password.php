<?php
$user_email = $_GET['user_email'] ?? '';
$user_token = $_GET['user_token'] ?? '';
$user_id = $_GET['user_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Reset Password</title>
  <meta name="description" content="">
  <link rel="stylesheet" href="./public/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="./public/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="./public/assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="./public/assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="./public/assets/css/custom.css">
  <link rel="shortcut icon" href="./public/assets/images/favicon.png" />

</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="./public/assets/images/logo.png" alt="logo">
              </div>
              <h4>Hello! Please reset your password</h4>
              <h6 class="font-weight-light">Please enter password and confirm password</h6>
              <form class="pt-3" id="moduleform" action="<?php echo $site_url;?>routes?action=reset-password">
                <div class="form-group">
                  <input type="hidden" name="user_email" id="user_email" value="<?php echo $user_email;?>" />
                  <input type="hidden" name="user_token" id="user_token" value="<?php echo $user_token;?>" />
                  <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>" />
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" placeholder="Password" name="password" />
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="cpassword"
                    placeholder="Confirm Password" id="cpassword" />
                </div>
                <div id="error" class="text-danger font-weight-bold"></div>
                <div class="mt-3">
                  <button type="submit" id="submit_button"
                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SUBMIT</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">

                  </div>
                  <a href="<?php echo $site_url;?>login" class="auth-link text-black">Back To Login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="./public/assets/vendors/js/vendor.bundle.base.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $("#moduleform").submit(function (e) {
        $("#submit_button").html("Please wait...");
        $("#error").val('');
        e.preventDefault();
        var form = $(this);
        var actionUrl = form.attr('action');
        let password = $("#password").val();
        let cpassword = $("#cpassword").val();
        if(password == ''){
          alert("Please enter your password");
        } else if(password.length < 8){
          alert("Please enter your password minimum 8 characters");
        } else if(cpassword == ''){
          alert("Please enter your confirm password");
        } else if(password != cpassword){
          alert("Password mismatch");
        } else {
          $.ajax({
            method: "POST",
            url: actionUrl,
            data: form.serialize(),
          }).done(function (response) {
            let data = JSON.parse(response);
            $("#submit_button").html("SUBMIT");
            if (data.success == 1) {
              window.location.href = '<?php echo $site_url;?>login';
            } else {
              alert("Error : " + JSON.stringify(data.message));
            }
          });
        }
      });
    });
  </script>
</body>

</html>