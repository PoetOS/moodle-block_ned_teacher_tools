if (typeof jQuery == "undefined") {
    document.write(unescape("%3Cscript type=\"text/javascript\" src=\"" + M.cfg.wwwroot + "/blocks/ned_teacher_tools/js/tablesorter/jquery-latest.js\"%3E%3C/script%3E"));
}
$(document).ready(function() {
    $("#datatable").tablesorter({
        sortList: [[2,0]],
        headers: {
            '.sorter-false' : {
                sorter: false
            }
        }
    });
    $("table.simplegradebook tr div.row-toggle").click(function() {
        $(this).toggleClass('highlighted');
        var selected = $(this).parent().parent();
        selected.toggleClass("highlight");
    });
});