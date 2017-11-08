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
$version = explode('.', $CFG->version);
$version = reset($version);

if ($version >= 2015051100) {
    // MOODLE 2.9.
    require_once($CFG->dirroot.'/blocks/ned_teacher_tools/forum_view_29.php');
} else if ($version >= 2014111000) {
    // MOODLE 2.8.
    require_once($CFG->dirroot.'/blocks/ned_teacher_tools/forum_view_28.php');
} else {
    // MOODLE 2.7.
    require_once($CFG->dirroot.'/blocks/ned_teacher_tools/forum_view_27.php');
}