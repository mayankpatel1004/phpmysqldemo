<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Default Page Title</title>
    <meta name="description" content="Default Meta Description" />
    <meta property="og:title" content="Default Meta Title" />
    <meta property="og:description" content="Default Meta Description" />
    <meta property="og:image" content="./public/images/default_profile_photo.png" />
    <meta property="og:url" content="https://example.com/products" />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="canonical" href="https://example.com/products/dry-fruits" />
    <!-- base:css -->
    <link rel="stylesheet" href="./public/assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="./public/assets/vendors/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="./public/assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="./public/assets/css/vertical-layout-light/style.css" />
    <link rel="stylesheet" href="./public/assets/css/vertical-layout-light/custom.css" />
    
    <link rel="stylesheet" href="./public/assets/vendors/select2/select2.min.css" />
    <link rel="stylesheet" href="./public/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css" />

    <!-- endinject -->
    <link rel="shortcut icon" href="./public/assets/images/favicon.png" />
    
    <!-- TinyMCE scripts - loaded early to ensure they are available when needed -->
    <script src="./public/assets/vendors/tinymce/tinymce.min.js"></script>
    <script src="./public/assets/vendors/tinymce/themes/modern/theme.js"></script>
</head>
<body>
    <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-left navbar-brand-wrapper d-flex align-items-center justify-content-between">
        <a class="navbar-brand brand-logo" href="/"><img src="./public/assets/images/emandi_logo.png" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="/"><img src="./public/assets/images/emandi_logo.png" alt="logo"/></a> 
        <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-menu"></span>
        </button>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item  dropdown d-none align-items-center d-lg-flex d-none">
            <a class="navbar-brand brand-logo" href="/"><img src="./public/assets/images/emandi_logo.png" class="w-25" alt="logo"/></a>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-user-icon">
            <a href="javascript:void(0)"><span id="localstorage_company_details" class="pt-2 text-primary">Welcome Administrator</span></a>
          </li>
          <li class="nav-item nav-user-icon float-left text-left margin-0 padding-0">
            <a class="text-primary" onclick="window.location.href='http://localhost/phpmysqldemo/logout'" href="javascript:void(0)">
              <i class="mdi mdi-logout-variant mdi-24px"></i>
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav><div class="page-body-wrapper">
    <div id="loader-overlay">
    <div>
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>
    </div>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
                        <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/">
                        <i class="mdi mdi-view-dashboard menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/item_section?item_type=default">
                        <i class="mdi mdi mdi-view-headline menu-icon"></i>
                        <span class="menu-title">Default Section</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/items?item_type=default">
                        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        <span class="menu-title">Default Items</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/items?item_type=page">
                        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        <span class="menu-title">Pages</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/item_section?item_type=blog">
                        <i class="mdi mdi mdi-view-headline menu-icon"></i>
                        <span class="menu-title">Blog Category</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/items?item_type=blog">
                        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        <span class="menu-title">Blogs</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/roles">
                        <i class="mdi mdi mdi-account menu-icon"></i>
                        <span class="menu-title">Roles</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/users">
                        <i class="mdi mdi-account-box menu-icon"></i>
                        <span class="menu-title">Users</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/change-password">
                        <i class="mdi mdi mdi-server-security menu-icon"></i>
                        <span class="menu-title">Change Password</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/metadetails">
                        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        <span class="menu-title">SEO</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/configurations">
                        <i class="mdi mdi-view-headline menu-icon"></i>
                        <span class="menu-title">Configurations</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/database_table">
                        <i class="mdi mdi mdi-view-headline menu-icon"></i>
                        <span class="menu-title">Database Tables</span>
                    </a>
                </li>
                                <li class="nav-item">
                                        <a class="nav-link" href="http://localhost/phpmysqldemo/logout">
                        <i class="mdi mdi-logout-variant menu-icon"></i>
                        <span class="menu-title">Logout</span>
                    </a>
                </li>
                    </ul>
</nav>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-white bg-primary p-2">Item Form</h4>
                    
                    <form id="userForm" method="POST" enctype="multipart/form-data">
                        <div class="row" id="formFields"></div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <span id="loading-button" class="small text-primary" style="display:none;">
                                    Please Wait....
                                </span>

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
            </div>
</div>
<script src="./public/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="./public/assets/js/off-canvas.js"></script>
<script src="./public/assets/js/hoverable-collapse.js"></script>
<script src="./public/assets/js/template.js"></script>
<script src="./public/assets/js/settings.js"></script>
<script src="./public/assets/js/todolist.js"></script>
<script src="./public/assets/vendors/sweetalert/sweetalert.min.js"></script>
<script src="./public/assets/vendors/select2/select2.min.js"></script>
<script src="./public/assets/js/dashboard.js"></script>

<script type="text/javascript">
// Global variable to track if tinymce is already initialized for dynamic fields
let tinyMCEInitialized = false;

// Function to initialize or re-initialize TinyMCE on elements with class 'tinyMceExample'
function initTinyMCE() {
    if (typeof tinymce === 'undefined') {
        console.warn('TinyMCE not loaded yet');
        return;
    }
    
    // Remove any existing editor instances to avoid duplication
    tinymce.remove();
    
    // Check if any textarea with class 'tinyMceExample' exists in the form
    if ($('.tinyMceExample').length) {
        tinymce.init({
            selector: '.tinyMceExample',
            height: 500,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
            image_advtab: true,
            templates: [{
                title: 'Test template 1',
                content: 'Test 1'
            },
            {
                title: 'Test template 2',
                content: 'Test 2'
            }],
            content_css: [],
            // Ensure that required attribute is respected
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save(); // Save content to textarea on change
                });
            }
        });
        tinyMCEInitialized = true;
    } else {
        tinyMCEInitialized = false;
    }
}

// Sync TinyMCE content to original textareas (useful before validation/submission)
function syncTinyMCE() {
    if (typeof tinymce !== 'undefined' && tinyMCEInitialized) {
        tinymce.triggerSave();
    }
}

$("#localstorage_company_details").html("Welcome "+localStorage.getItem('user_name'));

// Below is check and uncheall code start //
$("#selectAll").on("change", function () {
    $(".item").prop("checked", this.checked);
});

$(".item").on("change", function () {
    $("#selectAll").prop(
        "checked",
        $(".item:checked").length === $(".item").length
    );
});
// Below is check and uncheall code over //

function showLoader() {
  document.getElementById("loader-overlay").style.display = "flex";
}

function hideLoader() {
  document.getElementById("loader-overlay").style.display = "none";
}

</script>

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
            url: 'http://localhost/phpmysqldemo/routes?action=item_form&item_type=default&edit_id=30',
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
                    return;
                } 
                let fields = response.form_fields.fields;
                let listUrl = "http://localhost/phpmysqldemo/items?item_type=default";
                let html = '';

                $.each(fields, function (i, field) {
                    let isHidden = field.type === "hidden";
                    let required = field.req === "Y" ? "required" : "";
                    let requiredStar = field.req === "Y" ? '<span class="text-danger">*</span>' : '';
                    let colClass = isHidden ? 'd-none' : '';

                    html += `<div class="col-md-3 ${colClass}">`;

                    // LABEL (except hidden and checkbox)
                    if (!isHidden && field.type !== "checkbox") {
                        html += `<label for="${field.nm}" class="text-primary small">
                                    ${field.lbl || field.nm || ''} ${requiredStar}
                                </label>`;
                    }

                    // SELECT field
                    if (field.type === "select") {
                        let multiple = field.is_multiple === "Y" ? "multiple" : "";
                        let onchange = field.onchange ? `onchange="${field.onchange}"` : "";
                        html += `<select class="${field.cls || ''}" name="${field.nm}" id="${field.nm}" ${multiple} ${required} ${onchange}>`;
                        if (field.options) {
                            $.each(field.options, function (j, opt) {
                                let selected = (field.val == opt.id) ? "selected" : "";
                                html += `<option value="${opt.id}" ${selected}>
                                            ${opt.label || opt.id || ''}
                                        </option>`;
                            });
                        }
                        html += `</select>`;
                        
                        // Special handling for item_sections_id multiple select (preserve original logic)
                        if(field.nm == "item_sections_id" && field.val){
                            setTimeout(function(){
                                let field_value = field.val;
                                let arr_field_value = field_value.split(",").map(function(item) {
                                    return item.trim();
                                });
                                $('#item_sections_id').val(arr_field_value).trigger('change');
                            }, 700);
                        }
                    }
                    // CHECKBOX field
                    else if (field.type === "checkbox") {
                        html += `
                            <label for="${field.nm}" class="text-primary small">
                                ${field.lbl || field.nm || ''} ${requiredStar}
                            </label>
                            <input type="checkbox" id="${field.nm}" class="formfields ${field.cls || ''}"
                                name="${field.nm}" ${required} ${field.val == 1 ? 'checked' : ''}>
                        `;
                    }
                    // TEXTAREA field - IMPORTANT: use field.cls properly and populate value
                    else if (field.type === "textarea") {
                        // Fixed: use field.cls instead of fields.cls
                        let textareaClass = field.cls || '';
                        html += `<textarea id="${field.nm}" class="formfields ${textareaClass}" name="${field.nm}" ${required}>${field.val || ''}</textarea>`;
                    }
                    // INPUT field (text, file, hidden, number, etc.)
                    else {
                        let inputType = field.type || 'text';
                        let inputClass = field.cls || '';
                        let inputValue = (field.val !== undefined && field.val !== null) ? field.val : '';
                        // For file inputs, value attribute is ignored
                        let valueAttr = (inputType !== 'file') ? `value="${inputValue.replace(/"/g, '&quot;')}"` : '';
                        html += `<input 
                                    class="formfields ${inputClass}" 
                                    type="${inputType}" 
                                    name="${field.nm}" 
                                    id="${field.nm}"
                                    ${valueAttr}
                                    placeholder="${field.lbl || ''}"
                                    ${required}
                                >`;
                        // FILE PREVIEW LINK
                        if (inputType === "file" && field.val) {
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
                
                // Initialize select2 if needed (ensure any existing select2 elements are initialized)
                if ($('.select2').length) {
                    $('.select2').select2();
                }
                
                // Initialize TinyMCE on any textarea with class 'tinyMceExample' (or any class that indicates rich text)
                // This will handle dynamically added editors
                initTinyMCE();

                // Back button
                $("#backBtn").attr("href", listUrl);
            },
            error: function(xhr, status, error) {
                hideLoader();
                console.error("Error loading form data:", error);
                swal({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load form data. Please refresh the page.'
                });
            }
        });
    }

    function validateForm(event){
        // Sync TinyMCE content to textareas before validation
        syncTinyMCE();
        
        let is_error = 0;
        let errorMessage = '';
        
        // Loop through required fields and check values
        $('[required]').each(function(index, element) {
            // For checkboxes, required means it must be checked
            if ($(element).attr('type') === 'checkbox') {
                if (!$(element).is(':checked')) {
                    errorMessage = $(element).attr('placeholder') || $(element).attr('title') || $(element).attr('name') || 'This field';
                    errorMessage += ' is required.';
                    is_error = 1;
                    return false; // break loop
                }
            } 
            // For normal inputs and textareas
            else if ($(element).val() === '' || $(element).val() === null) {
                errorMessage = $(element).attr('placeholder') || $(element).attr('title') || $(element).attr('name') || 'This field';
                errorMessage += ' should not be blank.';
                is_error = 1;
                return false; // break loop
            }
        });
        
        if(is_error == 1){
            // Display error using sweetalert for better UX
            swal({
                icon: 'error',
                title: 'Validation Error',
                text: errorMessage
            });
            return false;
        }
        
        submitAdminForm(event);
        return false; // prevent default form submission
    }

    function submitAdminForm(e) {
        e.preventDefault();
        
        // Sync TinyMCE content one more time before submission to ensure latest data
        syncTinyMCE();
        
        $(".form-submit-button").hide();
        $("#loading-button").show();
        
        var formData = new FormData();
        // Use the actual form element to gather all fields including those not marked with class 'formfields'
        var $form = $('#userForm');
        
        // Add action and item_type parameters
        formData.append('action', 'item_form');
        formData.append('item_type', 'default');
        
        // Collect all input, select, textarea elements inside the form (excluding buttons)
        var allFields = $form.find('input, select, textarea').not('button[type="submit"]');
        
        allFields.each(function (i, field) {
            var $field = $(field);
            var name = $field.attr('name');
            if (!name) return;
            
            // Handle checkbox
            if ($field.attr('type') === 'checkbox') {
                formData.append(name, $field.is(':checked') ? 1 : 0);
            }
            // Handle file input
            else if ($field.attr('type') === 'file') {
                var files = $field[0].files;
                if (files.length > 0) {
                    $.each(files, function (idx, file) {
                        formData.append(name, file);
                    });
                }
            }
            // Handle all other inputs (text, hidden, textarea, select)
            else {
                formData.append(name, $field.val());
            }
        });
        
        // Debug log to verify form data
        console.warn("Submitting form with fields:");
        for (let pair of formData.entries()) {
            console.warn(pair[0] + ': ' + (pair[0].includes('file') ? '[FILE]' : pair[1]));
        }
        
        $.ajax({
            type: "POST",
            url: 'http://localhost/phpmysqldemo/routes',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (response1) {    
                let response;
                try {
                    response = JSON.parse(response1);
                } catch(e) {
                    console.error("JSON parse error:", e);
                    response = { success: 0, message: "Invalid server response" };
                }
                $("#loading-button").hide();
                $(".form-submit-button").show();
                
                if (response.success == 0) {
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
                        window.location.href = "http://localhost/phpmysqldemo/items?item_type=default";
                    });
                }
            },
            error: function (xhr, status, error) {
                $("#loading-button").hide();
                $(".form-submit-button").show();
                console.error("AJAX Error:", error, xhr.responseText);
                swal({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while submitting the form. Please try again.'
                });
            }
        });
    }
</script>
</body>
</html>