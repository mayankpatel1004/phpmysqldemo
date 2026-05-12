<div class="accordion accordion-solid-header" id="accordion-4" role="tablist"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function(){

    // API Response
    let responseData = {
        "configurations": [
            {
                "id": 1,
                "name": "Frontend Settings",
                "products": [
                    {
                        "id": 1,
                        "title": "Application Title",
                        "name": "FRONT_APPLICATION_TITLE",
                        "parent_id": 1,
                        "parent_name": "Frontend Settings",
                        "value": "CMS123",
                        "input_type": "text",
                        "options": null
                    },
                    {
                        "id": 2,
                        "title": "Records per page",
                        "name": "FRONT_RECORD_PER_PAGE",
                        "parent_id": 1,
                        "parent_name": "Frontend Settings",
                        "value": "16",
                        "input_type": "select",
                        "options": "8@=16@=24@=32@=40@=80"
                    }
                ]
            },
            {
                "id": 2,
                "name": "Backend Settings",
                "products": [
                    {
                        "id": 5,
                        "title": "Backend application Title",
                        "name": "BACKEND_APPLICATION_TITLE",
                        "parent_id": 2,
                        "parent_name": "Backend Settings",
                        "value": "Cloudswift :: Administrator",
                        "input_type": "text",
                        "options": null
                    }
                ]
            }
        ]
    };

    let html = '';

    $.each(responseData.configurations, function(parentIndex, parent){

        let headingId = 'heading-' + parent.id;
        let collapseId = 'collapse-' + parent.id;

        html += `
            <div class="card">

                <div class="card-header" role="tab" id="${headingId}">
                    <h6 class="mb-0">
                        <a data-toggle="collapse"
                           href="#${collapseId}"
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
                        <div class="row">
        `;

        // Child Loop
        $.each(parent.products, function(childIndex, child){

            html += `
                <div class="col-md-6 mb-3">

                    <div class="border p-3 rounded">

                        <label>
                            <strong>${child.title}</strong>
                        </label>
            `;

            // Text Input
            if(child.input_type == 'text' || child.input_type == 'email'){

                html += `
                    <input type="${child.input_type}"
                           class="form-control"
                           name="${child.name}"
                           value="${child.value}">
                `;
            }

            // Textarea
            else if(child.input_type == 'textarea'){

                html += `
                    <textarea class="form-control"
                              name="${child.name}"
                              rows="4">${child.value}</textarea>
                `;
            }

            // Select
            else if(child.input_type == 'select'){

                html += `
                    <select class="form-control"
                            name="${child.name}">
                `;

                if(child.options != null){

                    let options = child.options.split('@=');

                    $.each(options, function(i, option){

                        let selected = (option == child.value) ? 'selected' : '';

                        html += `
                            <option value="${option}" ${selected}>
                                ${option}
                            </option>
                        `;
                    });
                }

                html += `</select>`;
            }

            html += `
                    </div>

                </div>
            `;
        });

        html += `
                        </div>
                    </div>
                </div>

            </div>
        `;
    });

    // Append HTML
    $('#accordion-4').html(html);

});

</script>