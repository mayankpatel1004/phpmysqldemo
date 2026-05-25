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
                    <h4 class="card-title text-white bg-primary p-2">Dashboard overview</h4>
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php';?>
    </div>
</div>
<?php include 'partials/footerscript.php';?>
<script type="text/javascript">
    setTimeout(function(){
        getItems();
    },1000);
    function getItems(){
        $("#listing_display_data").html('');
        showLoader();
        $.ajax({
            type: "POST",
            url: '<?php echo $site_url;?>routes?action=dashboardfilter',
            data: {
                keyword: ''
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response) {
                hideLoader();
                let counter = 0;
                let final_response = JSON.parse(response);
                if(final_response.message == 'Invalid Token'){
                    swal({
                        icon: 'error',
                        title: 'Fail!',
                        text: final_response.message
                    }).then(() => {
                        window.location.href = "<?php echo $site_url; ?>login";
                    });
                } else {
                    
                    $("#loader").hide();
                }
            }
        });
    }
</script>
</body>
</html>