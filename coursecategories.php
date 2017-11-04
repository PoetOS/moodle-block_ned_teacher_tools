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
require_once($CFG->dirroot . '/blocks/ned_teacher_tools/coursecategories_form.php');

require_login();

// Permission.
require_capability('block/ned_teacher_tools:addinstance', context_system::instance(), $USER->id);

$title = get_string('coursecategories', 'block_ned_teacher_tools');
$heading = $SITE->fullname;

$PAGE->set_url('/blocks/ned_teacher_tools/coursecategories.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_context(context_system::instance());
$PAGE->set_title($title);
$PAGE->set_heading($heading);
$PAGE->set_cacheable(true);

$PAGE->requires->css('/blocks/ned_teacher_tools/css/styles.css');

$PAGE->requires->jquery();
$PAGE->requires->js('/blocks/ned_teacher_tools/js/selection.js');

$PAGE->navbar->add(get_string('pluginname', 'block_ned_teacher_tools'),
    new moodle_url('/admin/settings.php', array('section' => 'blocksettingned_teacher_tools')));

$PAGE->navbar->add(get_string('coursecategories', 'block_ned_teacher_tools'),
    new moodle_url('/blocks/ned_teacher_tools/coursecategories.php'));

$parameters = array();
$configcategory = get_config('block_ned_teacher_tools', 'category');
$configcourse = get_config('block_ned_teacher_tools', 'course');

$parameters = array(
    'course' => $configcourse,
    'category' => $configcategory
);

$mform = new coursecategory_form(null, $parameters, 'post', '', array('id' => 'notification_form', 'class' => 'notification_form'));

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', array('section' => 'blocksettingned_teacher_tools')),
        get_string('successful', 'block_ned_teacher_tools'));
} else if ($fromform = $mform->get_data()) {

    set_config('category', '',  'block_ned_teacher_tools');
    set_config('course', '',  'block_ned_teacher_tools');

    foreach ($_POST as $key => $value) {
        if (strpos($key, "category_") === 0) {
            if ($value <> '0') {
                $fromform->category[] = $value;
            }
        } else if (strpos($key, "course_") === 0) {
            if ($value <> '0') {
                $fromform->course[] = $value;
            }
        } else {
            $fromform->$key = $value;
        }
    }

    if (isset($fromform->category)) {
        $fromform->category = implode(',', $fromform->category);
        set_config('category', $fromform->category,  'block_ned_teacher_tools');
    }

    if (isset($fromform->course)) {
        $fromform->course = implode(',', $fromform->course);
        set_config('course', $fromform->course,  'block_ned_teacher_tools');
    }
    redirect(new moodle_url('/admin/settings.php', array('section' => 'blocksettingned_teacher_tools')),
        get_string('successful', 'block_ned_teacher_tools'));
    die;
}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();