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