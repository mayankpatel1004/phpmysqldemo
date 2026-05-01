<?php
unset($_SESSION['user']);
?>
<?php include 'partials/footerscript.php';?>
<script type="text/javascript">
    $(document).ready(function(){
        if(localStorage.getItem("login_name")){
            localStorage.removeItem("active_status");
            localStorage.removeItem("is_developer_account");
            localStorage.removeItem("login_name");
            localStorage.removeItem("site_db");
            localStorage.removeItem("site_id");
            localStorage.removeItem("user_email");
            localStorage.removeItem("user_id");
            localStorage.removeItem("user_name");
            localStorage.removeItem("user_role_id");
            localStorage.removeItem("web_or_app");
            localStorage.removeItem("active_status");
            localStorage.removeItem("token");
            window.location.href = '<?php echo $site_url."login";?>';
        }
    });
  </script>