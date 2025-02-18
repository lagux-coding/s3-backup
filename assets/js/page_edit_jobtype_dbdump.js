jQuery(document).ready(function ($) {
    $("#dball").click(function () {
        $('input[name="tabledb[]"]').prop("checked", true).change();
    });

    $("#dbnone").click(function () {
        $('input[name="tabledb[]"]').prop("checked", false).change();
    });

    $("#dbwp").click(function () {
        $('input[name="tabledb[]"]').prop("checked", false).change();
        $('input[name="tabledb[]"][value^="' + $("#dbwp").val() + '"]')
            .prop("checked", true)
            .change();
    });
});