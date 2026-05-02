<?php include 'partials/header.php';?>
<?php
$item_type = "";
$edit_id = 0;
if(isset($_GET['item_type']) && $_GET['item_type'] != ""){
    $item_type = $_GET['item_type'];
}
if(isset($_GET['edit_id']) && $_GET['edit_id'] > 0){
    $edit_id = $_GET['edit_id'];
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
                    <h4 class="card-title text-white bg-primary p-2">Role Form</h4>
                    
                    <form id="userForm" method="POST" enctype="multipart/form-data">
                        <div class="row" id="formFields"></div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <span id="loading-button" class="small text-primary" style="display:none;">
                                    Please Wait....
                                </span>

                                <div id="module_html" class="mt-4"></div>

                                <button type="submit" onclick="return validateForm(event);"
                                    class="btn btn-primary form-submit-button">
                                    Save
                                </button>

                                <a href="#" id="backBtn" class="btn btn-primary form-submit-button">Back</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <?php //include 'partials/footer.php';?>
    </div>
</div>
<?php include 'partials/footerscript.php';?>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(){
        setTimeout(function(){
            hideLoader();
            getFormData();
        }, 1000);
    });

    function getFormData(){
        $.ajax({
            type: "GET",
            url: '<?php echo $site_url;?>routes?action=role_form&item_type=<?php echo $item_type;?>&edit_id=<?php echo $edit_id;?>',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response1) {
                hideLoader();
                let response = JSON.parse(response1); 
                let fields = response.form_fields.fields;
                let listUrl = "<?php echo $site_url;?>item_section?item_type=default";
                let html = '';

                $.each(fields, function (i, field) {

                    let isHidden = field.type === "hidden";
                    let required = field.req === "Y" ? "required" : "";
                    let requiredStar = field.req === "Y" ? '<span class="text-danger">*</span>' : '';
                    let colClass = isHidden ? 'd-none' : '';

                    html += `<div class="col-md-3 ${colClass}">`;

                    // LABEL (except hidden)
                    if (!isHidden && field.type !== "checkbox") {
                        html += `<label for="${field.nm}" class="text-primary small">
                                    ${field.lbl || ''} ${requiredStar}
                                </label>`;
                    }

                    // SELECT
                    if (field.type === "select") {

                        let multiple = field.is_multiple === "Y" ? "multiple" : "";
                        let onchange = field.onchange ? `onchange="${field.onchange}"` : "";

                        html += `<select class="${field.cls}" name="${field.nm}" id="${field.nm}" ${multiple} ${required} ${onchange}>`;

                        if (field.options) {
                            $.each(field.options, function (j, opt) {
                                let selected = (field.val == opt.id) ? "selected" : "";
                                html += `<option value="${opt.id}" ${selected}>
                                            ${opt.label || ''}
                                        </option>`;
                            });
                        }

                        html += `</select>`;
                    }

                    // CHECKBOX
                    else if (field.type === "checkbox") {
                        html += `
                            <label for="${field.nm}" class="text-primary small">
                                ${field.lbl || ''} ${requiredStar}
                            </label>
                            <input type="checkbox" id="${field.nm}" class="formfields"
                                name="${field.nm}" ${required}>
                        `;
                    }

                    // INPUT (text, file, hidden, etc.)
                    else {
                        html += `<input 
                                    class="${field.cls || ''}" 
                                    type="${field.type}" 
                                    name="${field.nm}" 
                                    id="${field.nm}"
                                    value="${field.val || ''}" 
                                    placeholder="${field.lbl || ''}"
                                    ${required}
                                >`;

                        // FILE PREVIEW LINK
                        if (field.type === "file" && field.val) {
                            html += `<br>
                                    <a href="/public/uploads/${field.val}" target="_blank"
                                        class="small text-secondary">
                                        View File
                                    </a>`;
                        }
                    }

                    html += `</div>`;
                });

                $("#formFields").html(html);

                // Back button
                $("#backBtn").attr("href", listUrl);



                    let module_html = `
        <div class="h5 text-primary">Modules List</div>
        <table class="table text-primary table-striped">
            <thead>
                <tr class="bg-primary text-white text-center">
                    <th>ID</th>
                    <th>Module</th>
                    <th>View</th>
                    <th>Add</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>#</td>
                    <td><span class="text-primary">Check All / None</span></td>
                    <td><input type="checkbox" onchange="checkAll(this,'viewcolumn')" class="form-control" /></td>
                    <td><input type="checkbox" onchange="checkAll(this,'addcolumn')" class="form-control" /></td>
                    <td><input type="checkbox" onchange="checkAll(this,'editcolumn')" class="form-control" /></td>
                    <td><input type="checkbox" onchange="checkAll(this,'deletecolumn')" class="form-control" /></td>
                </tr>
    `;

    let modules = response.form_fields.modules || [];
    let role_access = response.form_fields.role_access || {};
    modules.forEach(row => {

        const roleAccessMap = {};
        role_access.forEach(r => {
            roleAccessMap[r.module_id] = r;
        });

        let access = roleAccessMap[row.meta_id] || {};
        const isViewChecked   = access.grant_view   === "Y" ? "checked" : "";
        const isAddChecked    = access.grant_add    === "Y" ? "checked" : "";
        const isEditChecked   = access.grant_edit   === "Y" ? "checked" : "";
        const isDeleteChecked = access.grant_delete === "Y" ? "checked" : "";

        

        module_html += `
            <tr>
                <td>${row.meta_id}</td>

                <td>
                    ${row.page_title}
                    <input type="hidden" name="module_id[]" 
                           value="${row.meta_id}" 
                           class="form-control formfields" />
                </td>

                <td>
                    <input type="checkbox"
                           id="${row.meta_id}_view"
                           accept="viewcolumn"
                           name="view[]"
                           class="form-control formfields viewcolumn"
                           ${isViewChecked}
                           />
                </td>

                <td>
                    <input type="checkbox"
                           id="${row.meta_id}_add"
                           accept="addcolumn"
                           name="add[]"
                           class="form-control formfields addcolumn"
                           ${isAddChecked}
                           />
                </td>

                <td>
                    <input type="checkbox"
                           id="${row.meta_id}_edit"
                           accept="editcolumn"
                           name="edit[]"
                           class="form-control formfields editcolumn"
                           ${isEditChecked}
                           />
                </td>

                <td>
                    <input type="checkbox"
                           id="${row.meta_id}_delete"
                           accept="deletecolumn"
                           name="delete[]"
                           class="form-control formfields deletecolumn"
                           ${isDeleteChecked}
                           />
                </td>
            </tr>
        `;
    });

    module_html += `</tbody></table>`;

    $("#module_html").html(module_html);


            }
        });
    }

    function checkAll(ele, alt) {
        
        var checkboxes = document.getElementsByTagName('input');
        if (ele.checked) {
          for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].accept == alt) {
              checkboxes[i].checked = true;
            }
          }
        } else {
          for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].accept == alt) {
              checkboxes[i].checked = false;
            }
          }
        }
      }

      function validateForm(event){
        let is_error = 0;
        $('[required]').each(function(index, element) {
            if(element.value == ''){
                $("#form_error").html(element.title+" should not blank.");
                is_error = 1;
            }
        });
        if(is_error == 0){
            submitAdminForm(event);
        }
    }


    function submitAdminForm(e) {
        e.preventDefault();
        $(".form-submit-button").hide();
        var formData = new FormData();
        var fields = $('.formfields');
        formData.append('action', 'role_form');
        formData.append('item_type', '<?php echo $item_type;?>');
        console.warn("Total Fields found:", fields.length);
        $.each(fields, function (i, field) {
            field = $(field);
            var name = field.attr('name');
            if (!name) {
                console.warn('Missing name attribute:', field);
                return;
            }
            if (field.attr('type') == 'checkbox') {
                formData.append(name, field.is(':checked') ? 1 : 0);
            } else if (field.attr('type') == 'file') {
                $.each(field[0].files, function (i, file) {
                formData.append(name, file);
                });
            } else {
                formData.append(name, field.val());
            }
        });
        console.warn("================= FormData entries: ==========");
        for (let pair of formData.entries()) {
            console.warn(pair[0] + ': ' + pair[1]);
        }
        $.ajax({
            type: "POST",
            url: '<?php echo $site_url;?>routes',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
        success: function (response1) {    
            let response = JSON.parse(response1);
            if (response.success == 0) {
                $("#loading-button").hide();
                $(".form-submit-button").show();
                swal({
                    icon: 'error',
                    title: 'Fail!',
                    text: response.message || 'An error occurred while saving your data.'
                });
            } else {
                swal({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Your data has been saved successfully.'
                }).then(() => {
                    window.location.href = "<?php echo $site_url; ?>roles?item_type=<?php echo $item_type;?>";
                });
            }
        },
        error: function (xhr, status, error) {
            $("#loading-button").hide();
            $(".form-submit-button").show();
            console.error("Error:", error);
        }
        });
    }


    </script>
</body>
</html>