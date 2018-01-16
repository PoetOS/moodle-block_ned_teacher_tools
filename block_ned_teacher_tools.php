<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block_ned_teacher_tools
 * @copyright  Michael Gardener <mgardener@cissq.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Simple  class for block ned_teacher_tools
 *
 * @copyright 2011 Moodlefn
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ned_teacher_tools extends block_list {

    /**
     * Sets the block title
     *
     * @return none
     */
    public function init() {
        $blocktitlesitelevel = get_config('block_ned_teacher_tools', 'blocktitlesitelevel');
        $this->title = $blocktitlesitelevel;
    }

    /**
     * Constrols the block title based on instance configuration
     *
     * @return bool
     */
    public function specialization() {

        if (! isset($this->config)) {
            $this->config = new stdClass;
        }
        $module = array('name' => 'block_ned_teacher_tools', 'fullpath' => '/blocks/ned_teacher_tools/module1.js');
        $this->page->requires->js_init_call('M.block_ned_teacher_tools.init_popup', null, false, $module);

        $blocktitlesitelevel = get_config('block_ned_teacher_tools', 'blocktitlesitelevel');
        $blocktitlecourselevel = get_config('block_ned_teacher_tools', 'blocktitlecourselevel');

        if (($this->page->course->id == SITEID) || ($this->instance->pagetypepattern == 'my-index') ) {
            $this->title = $blocktitlesitelevel;
            $this->page->requires->js_call_amd('block_ned_teacher_tools/filter_icon', 'render');
        } else {
            $this->title = $blocktitlecourselevel;
        }
        // Default setting values.
        if($this->page->course->id != SITEID) {
            if (empty($this->config->numberoflinks)) {
                $this->config->numberoflinks = 0;
            } else {
                $numberofcourselinks = $this->config->numberoflinks;
                for ($i = 0; $i <= $numberofcourselinks; $i++) {
                    $var = 'iconcode_'.$i;
                    if (!isset($this->config->$var)) {
                        $this->config->$var = 'fa-square-o';
                    }
                    $var = 'customlinkstitle_'.$i;
                    if (!isset($this->config->$var)) {
                        $this->config->$var = '';
                    }
                    $var = 'customlinkurl_'.$i;
                    if (!isset($this->config->$var)) {
                        $this->config->$var = '';
                    }
                    $var = 'linkbehaviour_'.$i;
                    if (!isset($this->config->$var)) {
                        $this->config->$var = '';
                    }
                }
            }
            if ($numberoflinks = get_config('block_ned_teacher_tools', 'numberoflinks')) {
                for ($i = 0; $i <= $numberoflinks; $i++) {
                    $var = 'sitelink_'.$i;
                    if (!isset($this->config->$var)) {
                        $this->config->$var = 1;
                    }
                }
            }
        }
    }

    public function has_config() {
        return true;
    }

    /**
     * Constrols the block title based on instance configuration
     *
     * @return bool
     */
    public function instance_allow_config() {
        return true;
    }

    /**
     * Creates the blocks main content
     *
     * @return string
     */
    public function get_content() {

        global $PAGE;

        // Need the bigger course stdClass.
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->text = '';
        $this->content->footer = '';

        if (($this->page->course->id == SITEID) || ($this->instance->pagetypepattern == 'my-index') ) {
            $PAGE->requires->jquery();
            $PAGE->requires->js('/blocks/ned_teacher_tools/js/collapse.js');
            $this->get_frontpage_content();
        } else {
            $this->get_standard_content();
        }

        return $this->content;
    }

    /**
     * Defines where the block can be added
     *
     * @return array
     */
    public function applicable_formats() {

        // Default case: the block can be used in all course types.
        return array(
            'all' => false,
            'site' => true,
            'course-*' => true,
            'my' => true
        );
    }

    /**
     * Function to return the standard content, used in all versions.
     *
     */
    private function get_standard_content() {

        global $CFG, $USER, $OUTPUT, $DB;

        require_once($CFG->dirroot . '/blocks/ned_teacher_tools/lib.php');
        require_once($CFG->dirroot . '/mod/forum/lib.php');

        $daysnotlogged = get_config('block_ned_teacher_tools', 'daysnotlogged');
        $daysnotsubmited = get_config('block_ned_teacher_tools', 'daysnotsubmited');
        $showtitles = get_config('block_ned_teacher_tools', 'showtitles');
        $percent = get_config('block_ned_teacher_tools', 'percent');
        $context = context_course::instance($this->page->course->id);

        $isteacher = has_capability('moodle/grade:viewall', $context);

        $showcourselink = get_config('block_ned_teacher_tools', 'showcourselink');

        if (!$isteacher) {
            return $this->content;
        }

        $sections = $DB->get_records('course_sections', array('course' => $this->page->course->id),
            'section ASC', 'section, sequence');

        // Course Teacher Menu.
        if (($this->page->course->id != SITEID)) {
            if ($showcourselink) {
                $this->content->items[] = '<a href="' . $CFG->wwwroot . '/course/view.php?id=' .
                    $this->page->course->id .
                    '">' . $this->page->course->shortname . '</a>';
                $this->content->icons[] = "<img src=\"" . $OUTPUT->pix_url('i/course') . "\" class=\"icon\" alt=\"\" />";
            }

            if ($showtitles) {
                $this->content->items[] = html_writer::div(get_string('markingmanager', 'block_ned_teacher_tools'), 'block-subtitle first');
                $this->content->icons[] = '';
            } else {
                if ($showcourselink) {
                    $this->content->items[] = "<div style='width:156px;'><hr /></div>";
                    $this->content->icons[] = '';
                }
            }

            // Settings.
            $cachedatalast = get_config('block_ned_teacher_tools', 'cachedatalast_'.$this->page->course->id);
            $refreshmodefrontpage = get_config('block_ned_teacher_tools', 'refreshmodefrontpage');
            $showunmarked = get_config('block_ned_teacher_tools', 'showunmarked');
            $showmarked = get_config('block_ned_teacher_tools', 'showmarked');
            $showunsubmitted = get_config('block_ned_teacher_tools', 'showunsubmitted');
            $showgradeslink = get_config('block_ned_teacher_tools', 'showgradeslink');
            $showgradebook = get_config('block_ned_teacher_tools', 'showgradebook');
            $showreportslink = get_config('block_ned_teacher_tools', 'showreportslink');
            $shownotloggedinuser = get_config('block_ned_teacher_tools', 'shownotloggedinuser');
            $showstudentnotsubmittedassignment = get_config('block_ned_teacher_tools', 'showstudentnotsubmittedassignment');
            $showstudentmarkslessthanfiftypercent = get_config('block_ned_teacher_tools', 'showstudentmarkslessthanfiftypercent');
            $isingroup = block_ned_teacher_tools_isinagroup($this->page->course->id, $USER->id);

            $supportedmodules = array_keys(block_ned_teacher_tools_supported_mods());
            list($insql, $params) = $DB->get_in_or_equal($supportedmodules);
            $params = array_merge(array($this->page->course->id), $params);
            if ($isingroup) {
                $params[] = $USER->id;
            } else {
                $params[] = 0;
            }

            $summary =  block_ned_teacher_tools_count_unmarked_activities($this->page->course, 'unmarked', '', $USER->id);
            $numunmarked = $summary['unmarked'];
            $nummarked = $summary['marked'];
            $numunsubmitted = $summary['unsubmitted'];
            $numsaved = $summary['saved'];

            if ($showunmarked) {
                $this->content->items[] = '<a href="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/fn_gradebook.php?courseid=' .
                    $this->page->course->id . '&show=unmarked' .
                    '&navlevel=top">'. $numunmarked.' ' .get_string('unmarked', 'block_ned_teacher_tools').'</a>';
                $this->content->icons[] = '<img src="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/pix/unmarked.gif"
                                                class="icon" alt="">';
            }

            if ($showmarked) {
                $this->content->items[] = '<a href="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/fn_gradebook.php?courseid=' .
                    $this->page->course->id . '&show=marked' .
                    '&navlevel=top">' . $nummarked . ' ' .get_string('marked', 'block_ned_teacher_tools').'</a>';
                $this->content->icons[] = '<img src="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/pix/graded.gif"
                                                class="icon" alt="">';
            }

            if ($showunsubmitted) {
                $this->content->items[] = '<a href="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/fn_gradebook.php?courseid=' .
                    $this->page->course->id . '&show=unsubmitted' .
                    '&navlevel=top">' . $numunsubmitted . ' '.get_string('unsubmitted', 'block_ned_teacher_tools').'</a>';
                $this->content->icons[] = '<img src="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/pix/not_submitted.gif"
                                                class="icon" alt="">';
            }

            if (isset($this->config->showsaved) && $this->config->showsaved) {
                $this->content->items[] = '<a href="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/fn_gradebook.php?courseid=' .
                    $this->page->course->id . '&show=saved' .
                    '&navlevel=top">' . $numsaved . ' '.get_string('saved', 'block_ned_teacher_tools').'</a>';
                $this->content->icons[] = '<img src="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/pix/saved.gif"
                                                class="icon" alt="">';
            }
            if ($showtitles) {
                $this->content->items[] = html_writer::div(get_string('quicklinks', 'block_ned_teacher_tools'), 'block-subtitle');
            } else {
                $this->content->items[] = "<div style='width:156px;'><hr /></div>";
            }
            $this->content->icons[] = '';

            if ($showgradeslink) {
                $this->content->items[] = '<a href="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/progress_report.php?id=' .
                    $this->page->course->id .
                    '">' . get_string('simplegradebook', 'block_ned_teacher_tools') . '</a>';
                $this->content->icons[] = "<img src=\"" . $OUTPUT->pix_url('i/grades') . "\" class=\"icon\" alt=\"\" />";
            }

            if ($showgradebook) {
                $this->content->items[] = '<a href="' . $CFG->wwwroot . '/grade/report/grader/index.php?id=' .
                    $this->page->course->id .
                    '">' . get_string('gradebook', 'block_ned_teacher_tools') . '</a>';
                $this->content->icons[] = "<img src=\"" . $OUTPUT->pix_url('i/report') . "\" class=\"icon\" alt=\"\" />";
            }

            if ($showreportslink) {
                $this->content->items[] = '<a href="' . $CFG->wwwroot . '/user/index.php?contextid='.$context->id.
                    '&sifirst=&silast=&roleid=5">' .
                    get_string('studentlist', 'block_ned_teacher_tools') . '</a>';
                $this->content->icons[] = "<img src=\"" . $OUTPUT->pix_url('i/group') . "\" class=\"icon\" alt=\"\" />";
            }

            // Custom links.
            $numberofcourselinks = $this->config->numberoflinks;
            $numberofsitelinks = get_config('block_ned_teacher_tools', 'numberoflinks');
            $enabledsitelinks = 0;

            if ($numberofsitelinks) {
                for ($i = 1; $i <= $numberofsitelinks; $i++) {
                    $var = 'sitelink_' . $i;
                    $enabledsitelinks += (int)$this->config->$var;
                }
            }

            if ($enabledsitelinks + $numberofcourselinks) {
                $this->content->items[] = html_writer::div(get_config('block_ned_teacher_tools', 'customlinkstitle'), 'block-subtitle');
            } else {
                $this->content->items[] = "<div style='width:156px;'><hr /></div>";
            }
            $this->content->icons[] = '';

            if ($enabledsitelinks) {
                for ($i = 1; $i <= $numberofsitelinks; $i++) {
                    $var = 'sitelink_' . $i;
                    $icon =  get_config('block_ned_teacher_tools', 'iconcode_' . $i);
                    $target =  get_config('block_ned_teacher_tools', 'linkbehaviour_' . $i);
                    $url =  get_config('block_ned_teacher_tools', 'customlinkurl_' . $i);
                    $title =  get_config('block_ned_teacher_tools', 'customlinkstitle_' . $i);

                    $cls = '';
                    if ($target == '_popup') {
                        $cls = 'popup';
                    }
                    if ( get_config('block_ned_teacher_tools', 'linkbehaviour_' . $i))
                    if ((int)$this->config->$var) {
                        $this->content->items[] = '<a class="'.$cls.'" href="'.$url.'" target="'.$target.'">'.$title.'</a>';
                        $this->content->icons[] = '<i class="fa '.$icon.'" aria-hidden="true"></i>';
                    }
                }
            }

            if ($numberofcourselinks) {
                for ($i = 1; $i <= $numberofcourselinks; $i++) {
                    $icon = 'iconcode_'.$i;
                    $title = 'customlinkstitle_'.$i;
                    $target = 'linkbehaviour_'.$i;
                    $url = 'customlinkurl_'.$i;

                    $cls = '';
                    if ($this->config->$target == '_popup') {
                        $cls = 'popup';
                    }

                    $this->content->items[] = '<a class="'.$cls.'" href="' . $this->config->$url . '" target="' .
                        $this->config->$target . '">' .
                        $this->config->$title . '</a>';
                    $this->content->icons[] = '<i class="fa ' . $this->config->$icon . '" aria-hidden="true"></i>';
                }
            }



            $now = time();
            $lastweek = $now - (60 * 60 * 24 * $daysnotsubmited);
            $numnotloggedin = block_ned_teacher_tools_count_notloggedin($this->page->course, $daysnotlogged);
            $numnotsubmittedany = block_ned_teacher_tools_get_notsubmittedany($this->page->course, $lastweek, true, $sections, null);
            $numfailing = block_ned_teacher_tools_count_failing($this->page->course, $percent);

            if ((($shownotloggedinuser && $numnotloggedin)
                || ($showstudentnotsubmittedassignment && $numnotsubmittedany)
                || ($showstudentmarkslessthanfiftypercent && $numfailing))) {
                if ($showtitles) {
                    $this->content->items[] = html_writer::div(get_string('notices', 'block_ned_teacher_tools'), 'block-subtitle');
                } else {
                    $this->content->items[] = "<div style='width:156px;'><hr /></div>";
                }
                $this->content->icons[] = '';
            }

            $strstudents = get_string('students');

            if ($shownotloggedinuser) {
                if ($numnotloggedin) {
                    $this->content->items[] = '<span class="fn_summaries"><a href="' .
                        $CFG->wwwroot . '/blocks/ned_teacher_tools/fn_summaries.php?id=' . $this->page->course->id . '&show=notloggedin' .
                        '&navlevel=top&days=' . $daysnotlogged . '&daysnotsubmited=' . $daysnotsubmited . '">' . $numnotloggedin . ' ' . $strstudents . ' </a>' .
                        get_string('notloggedin', 'block_ned_teacher_tools') . ' ' . $daysnotlogged . ' days</span>';
                    $this->content->icons[] = '<img src="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/pix/exclamation.png"
                    class="icon" alt="">';
                }
            }

            if ($showstudentnotsubmittedassignment) {
                if ($numnotsubmittedany) {
                    $this->content->items[] = '<span class="fn_summaries"><a href="' .
                        $CFG->wwwroot . '/blocks/ned_teacher_tools/fn_summaries.php?id=' . $this->page->course->id . '&show=notsubmittedany' .
                        '&navlevel=top&days=' . $daysnotlogged . '&daysnotsubmited=' . $daysnotsubmited . '">' . $numnotsubmittedany . ' ' . $strstudents . ' </a>' .
                        get_string('notsubmittedany', 'block_ned_teacher_tools') . '' . $daysnotsubmited . ' days</span>';
                    $this->content->icons[] = '<img src="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/pix/exclamation.png"
                    class="icon" alt="">';
                }
            }

            if ($showstudentmarkslessthanfiftypercent) {
                if ($numfailing) {
                    $this->content->items[] = '<span class="fn_summaries">
                    <a href="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/fn_summaries.php?id=' . $this->page->course->id . '&show=failing' .
                        '&navlevel=top&days=' . $daysnotlogged . '&daysnotsubmited=' . $daysnotsubmited . '&percent=' . $percent . '">' . $numfailing . ' ' . $strstudents . '</a>' .
                        get_string('overallfailinggrade', 'block_ned_teacher_tools') . '' . $percent . '% </span>';
                    $this->content->icons[] = '<img src="' . $CFG->wwwroot .
                        '/blocks/ned_teacher_tools/pix/exclamation.png" class="icon" alt="">';
                }
            }
        }
        return $this->content;
    }

    private function get_frontpage_content() {

        global $DB, $USER, $CFG, $OUTPUT;

        require_once($CFG->dirroot . '/blocks/ned_teacher_tools/lib.php');
        require_once($CFG->dirroot . '/mod/forum/lib.php');
        require_once($CFG->dirroot . '/course/lib.php');

        $modnames = get_module_types_names();
        $modnamesplural = get_module_types_names(true);

        $supportedmodules = array_keys(block_ned_teacher_tools_supported_mods());

        $isadmin   = is_siteadmin($USER->id);
        $text = '';

        $showzeroungraded = get_config('block_ned_teacher_tools', 'listcourseszeroungraded');

        $filtercourses = block_ned_teacher_tools_get_setting_courses();
        $userfilters = block_ned_teacher_tools_get_user_filter();

        if (is_array($userfilters)) {
            $filtercourses = array_intersect($filtercourses, $userfilters);
        }

        if ($filtercourses) {
            $filter = ' AND c.id IN ('.implode(',' , $filtercourses).')';
        } else {
            if (is_array($userfilters)) {
                $filter = ' AND 1 = 0';
            } else {
                $filter = '';
            }
        }

        // CACHE.
        $refreshmodefrontpage = get_config('block_ned_teacher_tools', 'refreshmodefrontpage');
        $minsbeforerefreshrequired = get_config('block_ned_teacher_tools', 'minsbeforerefreshrequired');
        $adminfrontpage = get_config('block_ned_teacher_tools', 'adminfrontpage');
        $refresh = '';

        // Courses - admin.
        if ($isadmin && $adminfrontpage == 'all') {

            $sqlcourse = "SELECT c.*
                            FROM {course} c
                           WHERE c.id > ?
                             AND c.visible = ?
                             $filter";
            $totalcoursenumber = $DB->count_records_sql('SELECT COUNT(c.id)
                                                           FROM {course} c
                                                          WHERE c.id > ?
                                                            AND c.visible = ? '.$filter,
                array(1, 1));

            if ($totalcoursenumber > 6) {
                $classforhide = 'block_ned_teacher_tools_hide';
                $classfordl = '';
            } else {
                $classforhide = '';
                $classfordl = ' class="expanded"';
            }

            if ($courses = $DB->get_records_sql($sqlcourse, array(1, 1))) {

                $text = block_ned_teacher_tools_build_ungraded_tree($courses, $supportedmodules, $classforhide, $showzeroungraded, 10);

                if ($refreshmodefrontpage == 'manual') {
                    $cachedatalast = block_ned_teacher_tools_frontapage_cache_update_time($USER->id);
                    if ($cachedatalast === false) {
                        $humantime = get_string('lastrefreshrequired', 'block_ned_teacher_tools').
                            html_writer::empty_tag('br');
                        $text = '';
                    } else if (($cachedatalast > 0) && (time() < $cachedatalast + $minsbeforerefreshrequired * 60)) {
                        $humantime = get_string('lastrefreshtime', 'block_ned_teacher_tools', block_ned_teacher_tools_human_timing($cachedatalast)).
                            html_writer::empty_tag('br');
                    } else {
                        $text = html_writer::div(get_string('listexpiredrefreshrequired', 'block_ned_teacher_tools'), 'list-expired-msg');
                        $humantime = '';
                    }

                    $refreshicon = html_writer::img($OUTPUT->pix_url('refresh_button', 'block_ned_teacher_tools'), '', null);
                    $refreshbutton = $refreshicon . ' ' . html_writer::link(
                            new moodle_url('/blocks/ned_teacher_tools/update_cache.php', array('id' => $this->page->course->id)),
                            get_string('refreshnow', 'block_ned_teacher_tools'),
                            array('class' => 'btn btn-secondary fn_refresh_btn')
                        );
                    $refresh = html_writer::div(
                        $humantime . $refreshbutton,
                        'fn_refresh_wrapper_footer'
                    );

                    $text .= "<div style='width:156px;'><hr /></div>" . $refresh;
                }


                if ($text) {
                    $this->content->items[] = '<div class="fn-collapse-wrapper"><dl class="expanded">' . $text . '</dl></div>';
                    $this->content->icons[] = '';
                }
            }
        } else {
            if ($teachercourses = block_ned_teacher_tools_teacher_courses($USER->id)) {
                $courses = array();
                foreach ($teachercourses as $teachercourse) {
                    if (empty($filtercourses) && is_array($userfilters)) {
                        continue;
                    }
                    if ($filtercourses) {
                        if (in_array($teachercourse->courseid, $filtercourses)) {
                            $course = $DB->get_record('course', array('id' => $teachercourse->courseid));
                            $courses[] = $course;
                        }
                    } else {
                        $course = $DB->get_record('course', array('id' => $teachercourse->courseid));
                        $courses[] = $course;
                    }
                }
                $text = block_ned_teacher_tools_build_ungraded_tree($courses, $supportedmodules, 'block_ned_teacher_tools_hide', $showzeroungraded);

                if ($refreshmodefrontpage == 'manual') {
                    $cachedatalast = block_ned_teacher_tools_frontapage_cache_update_time($USER->id);
                    if ($cachedatalast === false) {
                        $humantime = get_string('lastrefreshrequired', 'block_ned_teacher_tools').
                            html_writer::empty_tag('br');
                        $text = '';
                    } else if (($cachedatalast > 0) && (time() < $cachedatalast + $minsbeforerefreshrequired * 60)) {
                        $humantime = get_string('lastrefreshtime', 'block_ned_teacher_tools', block_ned_teacher_tools_human_timing($cachedatalast)).
                            html_writer::empty_tag('br');
                    } else {
                        $text = html_writer::div(get_string('listexpiredrefreshrequired', 'block_ned_teacher_tools'), 'list-expired-msg');
                        $humantime = '';
                    }

                    $refreshicon = html_writer::img($OUTPUT->pix_url('refresh_button', 'block_ned_teacher_tools'), '', null);
                    $refreshbutton = $refreshicon . ' ' . html_writer::link(
                            new moodle_url('/blocks/ned_teacher_tools/update_cache.php', array('id' => $this->page->course->id)),
                            get_string('refreshnow', 'block_ned_teacher_tools'),
                            array('class' => 'btn btn-secondary fn_refresh_btn')
                        );
                    $refresh = html_writer::div(
                        $humantime . $refreshbutton,
                        'fn_refresh_wrapper_footer'
                    );

                    $text .= "<div style='width:156px;'><hr /></div>" . $refresh;
                }

                if ($text) {
                    $this->content->items[] = '<div class="fn-collapse-wrapper"><dl class="expanded">' . $text . '</dl></div>';
                    $this->content->icons[] = '';
                }
            }
        }

        return $this->content;
    }
}
