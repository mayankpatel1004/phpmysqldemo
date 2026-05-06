<?php include 'partials/header.php';?>
<div class="page-body-wrapper">
    <div id="loader-overlay">
    <div>
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>
    </div>
    <?php include 'partials/sidebar.php';?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-white bg-primary p-2"><?php echo ucfirst($pageTitle); ?></h4>
                    <div class="row">
                        <div class="col-4 small">
                            New Password
                            <input type="text" name="password" id="password" class="form-control" placeholder="Enter Password" />
                        </div>
                        <div class="col-4 small">
                            Confirm Password
                            <input type="text" name="cpassword" id="cpassword" class="form-control" placeholder="Enter Confirm Password" />
                        </div>
                        <div class="col-4 small mt-3">
                            <input type="button" name="submit" class="btn btn-primary" value="Save" onclick="return fnChangePassword()" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 errors text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php';?>
    </div>
</div>
<?php include 'partials/footerscript.php';?>
<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){
            hideLoader();
        },500);
    });

    function fnChangePassword(){
        let error = '';
        $(".errors").html('');
        let password = $("#password").val();
        let cpassword = $("#cpassword").val();
        if(password == ''){
            error = "Please enter password";
        } else if(cpassword == ''){
            error = "Please enter confirm password";
        } else if(password.length < 8){
            error = "Please enter password greater then 8 characters";
        } else if(password != cpassword){
            error = "Your password mismatched";
        }

        if(error != ''){
            $(".errors").html(error);
        } else {
            showLoader();
            $.ajax({
                type: "POST",
                url: '<?php echo $site_url;?>routes?action=changepassword&action2=updatepassword',
                data: {
                    user_id : '<?php echo $_SESSION['user']['user_id'];?>',
                    password : $("#password").val(),
                    cpassword: $("#cpassword").val()
                },
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                success: function (response) {
                    hideLoader();
                    let final_response = JSON.parse(response);
                    if(final_response.success == 1){
                        location.reload();
                    } else {
                        alert(final_response.message);
                    }
                }
            });
        }
    }
</script>
</body>
</html>