/*
 * Collapse/Expand all courses/assessments. If we are in the course,
 * then only collapse/expand all assessments.
 */
function togglecollapseall(iscoursecontext) {
    if($('dl').hasClass('expanded')) {
        $('.toggle').removeClass('open');
        if (!iscoursecontext) {
            $('dd').addClass('block_ned_teacher_tools_hide');
        }
        $('dd ul').addClass('block_ned_teacher_tools_hide');
        $('dl').removeClass('expanded');
    } else {
        $('.toggle').addClass('open');
        if (!iscoursecontext) {
            $('dd').removeClass('block_ned_teacher_tools_hide');
        }
        $('dd ul').removeClass('block_ned_teacher_tools_hide');
        $('dl').addClass('expanded');
    }
}
$(document).ready(function() {
    $('div.fn-collapse-wrapper').parent().addClass("fn-full-width");
});