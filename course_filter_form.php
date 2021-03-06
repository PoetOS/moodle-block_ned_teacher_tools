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

require_once($CFG->libdir . '/formslib.php');

class course_filter_form extends moodleform {

    public function definition() {

        global $DB;

        $mform = $this->_form;

        $mform->addElement('header', '', get_string('coursetomonitor', 'block_ned_teacher_tools'), '');

        $courses = block_ned_teacher_tools_get_frontpage_courses();

        $courseitem = array();

        foreach ($courses as $courseid) {
            $course = $DB->get_record('course', array('id' => $courseid));
            $courseitem[] = &$mform->createElement('advcheckbox', $courseid, '', $course->fullname, array('name' => $courseid,'group'=>1), $courseid);
            $mform->setDefault("types[$courseid]", true);
        }

        $mform->addGroup($courseitem, 'courses', '', '<br>');

        $this->add_checkbox_controller(1);
        $this->add_action_buttons();
    }
}
