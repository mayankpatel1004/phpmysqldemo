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
                    <h4 class="card-title text-white bg-primary p-2">Item Section Form</h4>
                    
                    <form id="userForm" method="POST" enctype="multipart/form-data">
                        <div class="row" id="formFields"></div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <span id="loading-button" class="small text-primary" style="display:none;">
                                    Please Wait....
                                </span>

                                <div style="display:none;" id="submitbutton">
                                    <button type="submit" onclick="return validateForm(event);"
                                    class="btn btn-primary form-submit-button">
                                        Save
                                    </button>
                                    <a href="<?php echo $site_url;?>item_section?item_type=<?php echo $item_type;?>" id="backBtn" class="btn btn-primary form-submit-button">Back</a>
                                </div>
                                
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
            $("#submitbutton").show();
            getFormData();
        }, 1000);
    });

    function getFormData(){
        $.ajax({
            type: "GET",
            url: '<?php echo $site_url;?>routes?action=item_section_form&item_type=<?php echo $item_type;?>&edit_id=<?php echo $edit_id;?>',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response1) {
                hideLoader();
                let response = JSON.parse(response1); 
                if(response.success == 0){
                    swal({
                        icon: 'error',
                        title: 'Fail!',
                        text: response.message || 'An error occurred while saving your data.'
                    });
                }
                let fields = response.form_fields.fields;
                let listUrl = "<?php echo $site_url;?>item_section?item_type=<?php echo $item_type;?>";
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
                                    value="${field.val}" 
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
            }
        });
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
        formData.append('action', 'item_section_form');
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
                    window.location.href = "<?php echo $site_url; ?>item_section?item_type=<?php echo $item_type;?>";
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