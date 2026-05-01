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
            url: '<?php echo $site_url;?>routes?action=role_form&item_type=<?php echo $item_type;?>',
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
    modules.forEach(row => {
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
                           class="form-control formfields viewcolumn" />
                </td>

                <td>
                    <input type="checkbox"
                           id="${row.meta_id}_add"
                           accept="addcolumn"
                           name="add[]"
                           class="form-control formfields addcolumn" />
                </td>

                <td>
                    <input type="checkbox"
                           id="${row.meta_id}_edit"
                           accept="editcolumn"
                           name="edit[]"
                           class="form-control formfields editcolumn" />
                </td>

                <td>
                    <input type="checkbox"
                           id="${row.meta_id}_delete"
                           accept="deletecolumn"
                           name="delete[]"
                           class="form-control formfields deletecolumn" />
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

    </script>
</body>
</html>