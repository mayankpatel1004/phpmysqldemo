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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <td>
                                    <select class="form-control" name="tableName" id="tableName" onchange="getScreenData()">
                                        <option value="">Select Table Name</option>
                                    </select>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    
                        <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr id="Fields"></tr>
                        </thead>
                        <tbody id="AllRows">
                            
                        </tbody>
                    </table>
                            </div>
                    
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php';?>
    </div>
</div>
<?php include 'partials/footerscript.php';?>
<script type="text/javascript">
    function fnTableData(table_name){
        window.location.href = '<?php echo $site_url;?>database_table?tableName='+table_name;
    }
    $(document).ready(function(){
        getScreenData();
    });
    function getScreenData(){
        showLoader();
        $.ajax({
            type: "POST",
            url: '<?php echo $site_url;?>routes?action=databasefilter&action2=',
            data: {
                tableName : $("#tableName").val()
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response) {
                hideLoader();
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
                    let all_tables = final_response.all_tables;
                    let all_columns = final_response.arr_columns;
                    let all_rows = final_response.arr_rows;
                    let selected_table = final_response?.selected_table || '';
                
                    let all_tables_html = '';
                    if(all_tables && all_tables.length > 0){
                        all_tables_html += `<option value="">Select Table Name</option>`;
                        for (let table of all_tables) {
                            all_tables_html += `<option value="${table.Tables_in_Demonstration}">${table.Tables_in_Demonstration}</option>`;
                        }
                        $("#tableName").html(all_tables_html);
                        $("#tableName").val(selected_table);
                    }

                    let columns_html = '';
                    if(all_columns && all_columns.length > 0){
                        for (let table of all_columns) {
                            columns_html += `<td>${table.Field}</td>`;
                        }
                        $("#Fields").html(columns_html);
                    }

                    let rows_html = '';
                    if(all_rows && all_rows.length > 0){
                        
                        all_rows.forEach((data) => {
                            rows_html += '<tr>';
                            for (let i = 0; i < data.length; i++) {
                                rows_html += `<td>${data[i]}</td>`;
                            }
                            rows_html += '</tr>';
                        });

                        $("#AllRows").html(rows_html);
                    }   
                }
            }
        });
    }
</script>
</body>
</html>