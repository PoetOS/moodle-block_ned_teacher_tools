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

require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/ned_teacher_tools/lib.php');
require_once($CFG->dirroot . '/blocks/ned_teacher_tools/course_filter_form.php');

require_login();

$title = get_string('coursecategories', 'block_ned_teacher_tools');
$heading = $SITE->fullname;

$thispageurl = new moodle_url('/blocks/ned_teacher_tools/course_filter.php');
$returnurl = new moodle_url('/index.php');

$PAGE->set_url($thispageurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_context(context_system::instance());
$PAGE->set_title($title);
$PAGE->set_heading($heading);
$PAGE->set_cacheable(true);

$PAGE->requires->css('/blocks/ned_teacher_tools/css/styles.css');


$PAGE->navbar->add(get_string('pluginname', 'block_ned_teacher_tools'),
    new moodle_url('/admin/settings.php', array('section' => 'blocksettingned_teacher_tools')));

$PAGE->navbar->add(get_string('coursetomonitor', 'block_ned_teacher_tools'), new moodle_url($thispageurl));

$mform = new course_filter_form();

if ($mform->is_cancelled()) {
    redirect($returnurl, get_string('successful', 'block_ned_teacher_tools'));
} else if ($fromform = $mform->get_data()) {
    $courses = $fromform->courses;

    foreach ($courses as $index => $courseid) {
        if (empty($courseid)) {
            unset($courses[$index]);
        }
    }
    if ($courses) {
        $preference = implode(',', $courses);
    } else {
        // None of them selected.
        $preference = -1;
    }
    set_user_preference('block_ned_teacher_tools_coursefilter', $preference);
    redirect($returnurl, get_string('successful', 'block_ned_teacher_tools'));
    die;
}
echo $OUTPUT->header();
if ($selectedcourses = block_ned_teacher_tools_get_user_filter()) {
    $toform = new stdClass();
    $toform->courses = array();
    foreach ($selectedcourses as $selectedcourse) {
        $toform->courses[$selectedcourse] = $selectedcourse;
    }
    $mform->set_data($toform);
}
$mform->display();
echo $OUTPUT->footer();