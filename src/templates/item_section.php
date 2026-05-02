<?php include 'partials/header.php';?>
<?php
$item_type = "";
if(isset($_GET['item_type']) && $_GET['item_type'] != ""){
    $item_type = $_GET['item_type'];
}
?>
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
                    <h4 class="card-title text-white bg-primary p-2">Item Section</h4>
                    <table class="mb-2 w-100">
                        <thead>
                            <tr>
                                <td class="pull-left float-left"><input type="text" class="form-control-sm" placeholder="Search By Name" id="keyword" /></td>
                                <td class="pr-2">
                                    <select class="form-control" id="sort_by">
                                        <option value="">Sort By</option>
                                        <option value="item_section_id__desc">Newest First</option>
                                        <option value="item_section_id__asc">Oldest First</option>
                                    </select>
                                </td>
                                <td class="pull-left float-left"><input type="submit" class="btn btn-primary btn-sm" name="submit" value="Search" onclick="getItems(1);" /></td>
                                <td class="pull-left float-left"><a href="<?php echo $site_url."item_section?item_type=".$item_type?>" class="btn btn-primary btn-sm">Reset</a></td>
                                <td class="pr-2">
                                    <select class="form-control" id="update_status">
                                        <option value="">Action</option>
                                        <option value="A">Active</option>
                                        <option value="I">Inactive</option>
                                        <option value="T">Trash</option>
                                        <?php if($allow_delete_record == 'Y'):?>
                                        <option value="D">Delete</option>
                                        <?php endif;?>
                                    </select>
                                </td>
                                <td><input type="button" class="btn btn-primary btn-sm" name="submit" value="Submit" onclick="updateStatus()" /></td>
                                <td><a href="<?php $site_url;?>item_section_form?item_type=<?php echo $item_type;?>" class="btn btn-primary btn-sm">+</a>
                            </tr>
                        </thead>
                    </table>
                    <table class="table table-striped">
                        <thead>
                            <tr class="bg bg-primary text-white">
                                <th><input type="checkbox" name="chk_all" id="selectAll" value="1" /></th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Counter</th>
                                <th>ID</th>
                            </tr>
                        </thead>
                        <tbody id="listing_display_data">
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="12">
                                    <div class="float-right pull-right">
                                        <select id="current_page_no" class="form-control" onchange="getItems(this.value);">
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php';?>
    </div>
</div>
<?php include 'partials/footerscript.php';?>
<script type="text/javascript">
    setTimeout(function(){
        getItems(1);
    },1000);
    function updateStatus(){
        showLoader();
        const ids = [...document.querySelectorAll(".item:checked")].map(cb => cb.value).join(",");
        
        $.ajax({
            type: "POST",
            url: '<?php echo $site_url;?>routes?action=itemssectionfilter&action2=updatestatus',
            data: {
                status : $("#update_status").val(),
                keyword: $("#keyword").val(),
                sort_by : '',
                ids: ids,
                item_type: '<?php echo $item_type;?>',
                page_no: $("#page_no").val()
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
    function getItems(page_no = 1){
        $("#listing_display_data").html('');
        let pagination_html = '';
        let sort_by = '';
        if($("#sort_by").val() != ""){
            sort_by = $("#sort_by").val();
        }
        showLoader();
        $.ajax({
            type: "POST",
            url: '<?php echo $site_url;?>routes?action=itemssectionfilter',
            data: {
                keyword: $("#keyword").val(),
                sort_by : sort_by,
                item_type: '<?php echo $item_type;?>',
                page_no: page_no
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response) {
                hideLoader();
                let counter = 0;
                let final_response = JSON.parse(response);
                let items = final_response.data;
                if (items && items.length > 0) {
                    $.each(items, function (index, item) {
                        counter++;
                        $("#listing_display_data").append(`<tr>
                            
                            <td><input type="checkbox" class="checkbox_css item ml-3 sales_summary_list_checkbox" name="chk[]" id="chk_${item.item_section_id}" accept="allitems" value="${item.item_section_id}" /></td>

                            <td><a href="{{formUrl}}&edit_id=${item.item_section_id}">${item.section_title}</a></td>

                            <td>${item.item_type}</td>
                            
                            <td>${item.display_order}</td>
                            
                            <td><span class="badge badge-${item.display_status == 'Yes' ? 'success' : 'danger'}">${item.display_status}</span></td>
                            
                            <td>${item.created_at}</td>
                            
                            <td>${item.updated_at}</td>
                            
                            <td>${counter}</td>
                            
                            <td>${item.item_section_id}</td>

                        </tr>`);
                    });

                    let page_number = $("#current_page_no").val();
                    if (final_response.total_pages == 1) {
                        pagination_html += `<option value="1">1</option>`;
                    } else {
                        pagination_html += `<option value="">Go To</option>`;
                        for (var i=1;i<=final_response.total_pages;i++){
                            pagination_html += `<option value="${parseInt(i)}">${parseInt(i)}</option>`;
                        }
                    }
                    $("#current_page_no").html(pagination_html);
                    $("#current_page_no").val(final_response.current_page_no);
                } else {
                    $("#listing_display_data").html(`
                    <tr>
                    <td colspan="100%" class="text-center text-secondary">No Data Found</td>
                    </tr>
                `);
                }
                $("#loader").hide();
            }
        });
    }
</script>
</body>
</html>