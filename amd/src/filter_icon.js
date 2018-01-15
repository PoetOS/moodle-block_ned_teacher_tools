/* jshint ignore:start */
define(['jquery'], function($) {
    return {
        render: function() {
            $(document).ready(function() {
                console.log('block_ned_teacher_tools/filter_icon::render');
                var title = $('.block_ned_teacher_tools .header .title h2');
                if (!title.length) {
                    // BOOST theme.
                    title = $('.block_ned_teacher_tools .card-block .card-title');
                }
                console.log(title);
                var url = M.cfg.wwwroot + '/blocks/ned_teacher_tools/course_filter.php';
                title.append('<div class="filter-icon"><a href="' + url +
                    '"><img src="' + M.util.image_url('i/filter', '') + '" alt=""></a></div>');
                //M.util.get_string();
            });
        }
    };
});
/* jshint ignore:end */