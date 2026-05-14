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
                    <form name="configform" id="configform" action="<?php echo $site_url;?>routes?action=saveconfig">
                        <div id="accordionresponse"></div>
                        <!-- Below is for support only Start -->
                        <div class="accordion accordion-solid-header d-none" id="accordion-4" role="tablist">
                            <div class="card">
                                <div class="card-header" role="tab" id="heading-10">
                                    <h6 class="mb-0">
                                        <a data-toggle="collapse" href="#collapse-10" aria-expanded="true"
                                            aria-controls="collapse-10">
                                            How can I pay for an order I placed?
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapse-10" class="collapse" role="tabpanel" aria-labelledby="heading-10"
                                    data-parent="#accordion-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <img src="https://via.placeholder.com/93x93" class="mw-100" alt="image">
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-0">You can pay for the product you have purchased using credit
                                                    cards, debit cards, or via online banking.
                                                    We also on-delivery services.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Below is for support only Over -->
                        <input type="submit" name="submit" class="btn submit-button btn-primary d-none" value="Save" />
                    </form>
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php';?>
    </div>
</div>
<?php include 'partials/footerscript.php';?>
<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() {
        getItems();
    }, 200);

    $("#configform").submit(function(e){
        e.preventDefault();
        $("#login_error").html("");
        $(".submit-button").val("Please wait...");
        var form = $(this);
        var actionUrl = form.attr('action');
        $.ajax({
            type: "POST",
            url: actionUrl,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            data: form.serialize(),
            success: function (data) {
                $(".submit-button").val("Save");
                let response = JSON.parse(data);
                if (response.success == 1) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });
});

function getItems() {
    showLoader();
    $.ajax({
        type: "POST",
        url: '<?php echo $site_url;?>routes?action=site_configurations',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function(response) {
            let data = JSON.parse(response);
            hideLoader();
            $("#loader").hide();

            let html = '';
            $.each(data.configurations, function(parentIndex, parent) {
                let headingId = 'heading-' + parent.id;
                let collapseId = 'collapse-' + parent.id;
                html += `
            <div class="card">
                <div class="card-header bg-primary" role="tab" id="${headingId}">
                    <h6 class="mb-0">
                        <a data-toggle="collapse"
                           href="#${collapseId}"
                           class="text-white"
                           aria-expanded="${parentIndex == 0 ? 'true' : 'false'}"
                           aria-controls="${collapseId}">
                            ${parent.name}
                        </a>
                    </h6>
                </div>
                <div id="${collapseId}"
                     class="collapse ${parentIndex == 0 ? 'show' : ''}"
                     role="tabpanel"
                     aria-labelledby="${headingId}"
                     data-parent="#accordion-4">
                    <div class="card-body">
                        <div class="row">`;

                // Child Loop
                $.each(parent.products, function(childIndex, child) {

                    html += `<div class="col-md-6 mb-3">
                    <div class="border p-3 rounded">
                        <label><strong>${child.title}</strong></label>`;

                    // Text Input
                    if (child.input_type == 'text' || child.input_type == 'email') {
                        html += `
                    <input type="${child.input_type}"
                           class="form-control"
                           name="${child.name}"
                           value="${child.value}">`;
                    }

                    // Textarea
                    else if (child.input_type == 'textarea') {
                        html += `
                    <textarea class="form-control"
                              name="${child.name}"
                              rows="4">${child.value}</textarea>`;
                    }

                    // Select
                    else if (child.input_type == 'select') {
                        html += `
                    <select class="form-control"
                            name="${child.name}">`;
                        if (child.options != null) {
                            let options = child.options.split('@=');
                            $.each(options, function(i, option) {
                                let selected = (option == child.value) ?
                                    'selected' : '';
                                html += `
                            <option value="${option}" ${selected}>
                                ${option}
                            </option>`;
                            });
                        }
                        html += `</select>`;
                    }
                    html += `
                    </div>
                </div>`;
                });

                html += `
                        </div>
                    </div>
                </div>
            </div>`;
            });

            // Append HTML
            $('#accordionresponse').html(html);
            $(".submit-button").removeClass('d-none');
        }
    });
}
</script>
</body>
</html>