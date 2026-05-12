<?php
$user_email = $_GET['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Password Token</title>
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
              <h4>Hello! User</h4>
              <h6 class="font-weight-light">Please enter your token which you will reseived over your email.</h6>
              <form class="pt-3" method="POST" id="password-token" action="<?php echo $site_url;?>routes?action=password-token">
                <div class="form-group">
                  <input type="hidden" name="user_email" id="user_email" value="<?php echo $user_email;?>" />
                  <input type="text" class="form-control form-control-lg" name="user_token" id="user_token"
                    placeholder="Token" value="" />
                </div>
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
    $("#password-token").submit(function (e) {
      $("#login_error").html("");
      $("#submit_button").html("Please wait...");
      e.preventDefault();
      var form = $(this);
      var actionUrl = form.attr('action');
      $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(),
        success: function (data) {
          $("#submit_button").html("SUBMIT");
          let response = JSON.parse(data);
          if (response.success == 1) {
              let user_id = response.user_id;
              let user_email = response.user_email;
              let user_token = response.user_token;
              window.location.href = '<?php echo $site_url;?>reset-password?user_id='+user_id+'&user_email=' + user_email+"&user_token="+user_token;
          } else {
            alert(response.message);
          }
          
        }
      });
    });
  </script>
</body>

</html>