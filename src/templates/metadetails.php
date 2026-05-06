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
                    <h4 class="card-title text-white bg-primary p-2">Meta Details</h4>

                    <table class="table table-striped">
                        <thead><tr class="bg-primary text-white small" id="all_columns"></tr></thead>
                        <tbody id="all_rows"></tbody>
                    </table>
                    <input type="button" class="btn btn-primary mt-2 btn-sm" value="Submit" onclick="saveMetaDetails(event)" />
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php';?>
    </div>
</div>
<?php include 'partials/footerscript.php';?>
<script type="text/javascript">
    function saveMetaDetails(e) {
        e.preventDefault();
        var values = {};
        var fields = $('.formfields');
        $.each(fields, function (i, field) {
            let dom = '';
            if ($(field).attr('type') == 'checkbox') {
                dom = $(field), name = dom.attr('id'), value = dom.is(':checked') ? 1 : 0;
            } else {
                dom = $(field), name = dom.attr('id'), value = dom.val(), checked = dom.is(':selected');
            }
            values[name] = value;
        });
        $.ajax({
            type: "POST",
            url: '<?php echo $site_url;?>routes?action=metadetailsfilter&action2=updatefields',
            data: values,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response) {
                location.reload();
            }
        });
    }

    $(document).ready(function(){
        getScreenData();
    });
    function getScreenData(){
        showLoader();
        $.ajax({
            type: "POST",
            url: '<?php echo $site_url;?>routes?action=metadetailsfilter&action2=',
            data: {
                tableName : $("#tableName").val()
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response) {
                hideLoader();
                let final_response = JSON.parse(response);
                let all_columns = final_response.columns;
                let all_rows = final_response.data;
                

                let columns_html = '';
                if(all_columns && all_columns.length > 0){
                    all_columns.forEach((field) => {
                        columns_html += `<td class="text-white font-weight-bold">${field}</td>`;
                    });
                    $("#all_columns").html(columns_html);
                }

                let html = '';
                all_rows.forEach(item => {
                    html += `<tr>
                        <td>${item.meta_id}</td>
                        <td><input type="text" class="form-control-sm w-100 formfields" value="${item.page_title}" name="page_title" id="page_title__${item.meta_id}" /></td>
                        <td>${item.sidebar_title}</td>
                        <td>${item.end_points}</td>
                        <td><input type="text" class="form-control-sm w-100 formfields" value="${item.meta_title}" name="meta_title" id="meta_title__${item.meta_id}" /></td>
                        <td><input type="text" class="form-control-sm w-100 formfields" value="${item.meta_description}" name="meta_description" id="meta_description__${item.meta_id}" /></td>
                        <td>${item.sidebar_order}</td>
                    </tr>`;
                });

                $("#all_rows").html(html);
            }
        });
    }
</script>
</body>
</html>