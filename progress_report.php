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

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/lib/plagiarismlib.php');
require_once('lib.php');
require_once($CFG->dirroot . '/lib/outputrenderers.php');
require_once($CFG->dirroot . '/mod/forum/lib.php');

// One of these is necessary!
$courseid = optional_param('id', 0, PARAM_INT);
$group = optional_param('group', 0, PARAM_INT);
$showsuspendedusers = optional_param('showsuspendedusers', 1, PARAM_INT);
$unsubmitted = optional_param('unsubmitted', '0', PARAM_INT);

$sort = optional_param('sort', 'name', PARAM_TEXT);
$dir = optional_param('dir', 'ASC', PARAM_ALPHA);

$blocksettings = block_ned_teacher_tools_get_block_config($courseid, 'ned_teacher_tools');

$SESSION->currentgroup[$courseid] = $group;

if ($courseid) {
    if (! $course = $DB->get_record('course', array('id' => $courseid))) {
        print_error('coursemisconf');
    }
} else {
    print_error('coursemisconf');
}

require_course_login($course);

// Array of functions to call for grading purposes for modules.
$modgradesarray = block_ned_teacher_tools_supported_mods();

$context = context_course::instance($course->id);
$isteacher = has_capability('moodle/grade:viewall', $context);

$cobject = new stdClass();
$cobject->course = $course;

if (!$isteacher) {
    print_error("Only teachers can use this page!");
}
$PAGE->requires->jquery();
$PAGE->requires->js('/blocks/ned_teacher_tools/js/tablesorter/jquery.tablesorter.js');
$PAGE->requires->js('/blocks/ned_teacher_tools/js/tablesorter/jquery.metadata.js');
$PAGE->requires->js('/blocks/ned_teacher_tools/js/sort.js');
$PAGE->requires->js('/blocks/ned_teacher_tools/textrotate.js');
$PAGE->requires->js_function_call('textrotate_init', null, true);
$PAGE->requires->css('/blocks/ned_teacher_tools/css/styles.css');
$PAGE->requires->css('/blocks/ned_teacher_tools/js/tablesorter/themes/blue/style.css');

$PAGE->set_url(
    new moodle_url('/blocks/ned_teacher_tools/progress_report.php'),
    array('id' => $courseid)
);

if ($layout = get_config('block_ned_teacher_tools', 'pagelayout')) {
    $PAGE->set_pagelayout($layout);
} else {
    $PAGE->set_pagelayout('course');
}

$PAGE->set_context($context);

// Suspended users view,
list($onlyactiveenrollments, $showsuspendedaccounts) = block_ned_teacher_tools_suspended_users_view_vars($course->id);

if (!$showsuspendedusers) {
    $onlyactiveenrollments = true;
    $showsuspendedaccounts = false;
}

// If comes from course page.
$currentgroup = $SESSION->currentgroup[$course->id];

$groupstudents = block_ned_teacher_tools_mygroup_members($course->id, $USER->id);
if (is_array($groupstudents) && ($currentgroup === 0)) {
    $students = $groupstudents;
} else {
    $students = get_enrolled_users($context, 'mod/assign:submit', $currentgroup, 'u.*', 'u.suspended ASC,u.firstname ASC', 0, 0, $onlyactiveenrollments);
}

$simplegradebook = array();
$weekactivitycount = array();

foreach ($students as $key => $value) {
    if ($onlyactiveenrollments) {
        $supendedenrollment = false;
    } else {
        $supendedenrollment = block_ned_teacher_tools_is_suspended_enrolment($course->id, $value->id);
    }
    if ($showsuspendedaccounts) {
        $simplegradebook[$key]['name'] = $value->firstname . ' ' . substr($value->lastname, 0, 1) . '.';
        $simplegradebook[$key]['suspended'] = $value->suspended || $supendedenrollment;
    } else {
        if (!$value->suspended) {
            $simplegradebook[$key]['name'] = $value->firstname . ' ' . substr($value->lastname, 0, 1) . '.';
            $simplegradebook[$key]['suspended'] = $value->suspended ||$supendedenrollment;
        }
    }
}

// Get a list of all students.
if (!$students) {
    $students = array();
    $PAGE->set_title(get_string('course') . ': ' . $course->fullname);
    $PAGE->set_heading($course->fullname);
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string("nostudentsyet"));
    echo $OUTPUT->footer($course);
    exit;
}

// Collect modules data.
$modnames = get_module_types_names();
$modnamesplural = get_module_types_names(true);
$modinfo = get_fast_modinfo($course->id);
$mods = $modinfo->get_cms();
$modnamesused = $modinfo->get_used_module_names();

$modarray = array($mods, $modnames, $modnamesplural, $modnamesused);

$cobject->mods = &$mods;
$cobject->modnames = &$modnames;
$cobject->modnamesplural = &$modnamesplural;
$cobject->modnamesused = &$modnamesused;
$cobject->sections = &$sections;


// FIND CURRENT WEEK.
$courseformatoptions = course_get_format($course)->get_format_options();
$courseformat = course_get_format($course)->get_format();

if (isset($courseformatoptions['numsections'])) {
    $coursenumsections = $courseformatoptions['numsections'];
} else {
    if (!$coursenumsections = $DB->count_records('course_sections', array('course' => $course->id))) {
        $coursenumsections = 10; // Default section number.
    }
}


$timenow = time();
$weekdate = $course->startdate;    // This should be 0:00 Monday of that week.
$weekdate += 7200;                 // Add two hours to avoid possible DST problems.

$weekofseconds = 604800;
$courseenddate = $course->startdate + ($weekofseconds * $coursenumsections);

// Calculate the current week based on today's date and the starting date of the course.
$currentweek = ($timenow > $course->startdate) ? (int) ((($timenow - $course->startdate) / $weekofseconds) + 1) : 0;
$currentweek = min($currentweek, $coursenumsections);

// Search through all the modules, pulling out grade data.
$sections = get_fast_modinfo($course->id)->get_section_info_all();
$upto = count($sections);

for ($i = 0; $i < $upto; $i++) {
    $numberofitem = 0;
    if (isset($sections[$i])) {
        $section = $sections[$i];
        if ($section->sequence) {
            $sectionmods = explode(",", $section->sequence);
            foreach ($sectionmods as $sectionmod) {
                if (empty($mods[$sectionmod])) {
                    continue;
                }

                $mod = $mods[$sectionmod];
                if (! isset($modgradesarray[$mod->modname])) {
                    continue;
                }
                // Don't count it if you can't see it.
                $mcontext = context_module::instance($mod->id);
                if (!$mod->visible && !has_capability('moodle/course:viewhiddenactivities', $mcontext)) {
                    continue;
                }
                $instance = $DB->get_record($mod->modname, array("id" => $mod->instance));
                if (!$item = $DB->get_record('grade_items', array("itemtype" => 'mod', "itemmodule" => $mod->modname,
                    "iteminstance" => $mod->instance))) {
                    $item = new stdClass();
                    $item->gradepass = 0;
                    $item->grademax = 0;
                }

                $libfile = $CFG->dirroot . '/mod/' . $mod->modname . '/lib.php';
                if (file_exists($libfile)) {
                    require_once($libfile);
                    $gradefunction = $mod->modname . "_get_user_grades";

                    if ((($mod->modname != 'forum') || ($instance->assessed > 0))
                        && isset($modgradesarray[$mod->modname])) {

                        if (function_exists($gradefunction)) {
                            ++$numberofitem;
                            $mod->modname == 'quiz';

                            $image = "<A target='_blank' HREF=\"$CFG->wwwroot/mod/$mod->modname/view.php?id=$mod->id\"
                                TITLE=\"$instance->name\"><IMG BORDER=0 VALIGN=absmiddle
                                SRC=\"".$OUTPUT->pix_url('icon', $mod->modname)."\"
                                HEIGHT=16 WIDTH=16 ALT=\"$mod->modfullname\"></A>";


                            $weekactivitycount[$i]['mod'][] = $image;
                            $weekactivitycount[$i]['modname'][] = $instance->name;
                            foreach ($simplegradebook as $key => $value) {
                                $fnurl = new moodle_url(
                                    '/blocks/ned_teacher_tools/fn_gradebook.php',
                                    array(
                                        'courseid' => $mod->course,
                                        'dir' =>' DESC',
                                        'sort' => 'date',
                                        'show' => 'unmarked',
                                        'unsubmitted' => 0,
                                        'activity_type' => 0,
                                        'view' => 'less',
                                        'group' => 0,
                                        'participants' => $key
                                    )
                                );
                                $simplegradebook[$key]['url'] = $fnurl;

                                if (($mod->modname == 'quiz') || ($mod->modname == 'forum')) {

                                    if ($grade = $gradefunction($instance, $key)) {
                                        if ($item->gradepass > 0) {
                                            if ($grade[$key]->rawgrade >= $item->gradepass) {
                                                $simplegradebook[$key]['grade'][$i][$mod->id] = 'marked.gif'; // Passed.
                                                $simplegradebook[$key]['avg'][] = array('grade' => $grade[$key]->rawgrade,
                                                    'grademax' => $item->grademax);
                                            } else {
                                                $simplegradebook[$key]['grade'][$i][$mod->id] = 'incomplete.gif'; // Fail.
                                                $simplegradebook[$key]['avg'][] = array('grade' => $grade[$key]->rawgrade,
                                                    'grademax' => $item->grademax);
                                            }
                                        } else {
                                            // Graded (grade-to-pass is not set).
                                            $simplegradebook[$key]['grade'][$i][$mod->id] = 'graded_.gif';
                                            $simplegradebook[$key]['avg'][] = array('grade' => $grade[$key]->rawgrade,
                                                'grademax' => $item->grademax);
                                        }
                                    } else {
                                        $simplegradebook[$key]['grade'][$i][$mod->id] = 'ungraded.gif';
                                        if ($unsubmitted) {
                                            $simplegradebook[$key]['avg'][] = array('grade' => 0, 'grademax' => $item->grademax);
                                        }
                                    }
                                } elseif ($mod->modname == 'journal') {

                                    if ($grade = $gradefunction($instance, $key)) {
                                        if (is_numeric($grade[$key]->rawgrade)) {
                                            if ($item->gradepass > 0) {
                                                if ($grade[$key]->rawgrade >= $item->gradepass) {
                                                    $simplegradebook[$key]['grade'][$i][$mod->id] = 'marked.gif'; // Passed.
                                                    $simplegradebook[$key]['avg'][] = array('grade' => $grade[$key]->rawgrade,
                                                        'grademax' => $item->grademax);
                                                } else {
                                                    $simplegradebook[$key]['grade'][$i][$mod->id] = 'incomplete.gif'; // Fail.
                                                    $simplegradebook[$key]['avg'][] = array('grade' => $grade[$key]->rawgrade,
                                                        'grademax' => $item->grademax);
                                                }
                                            } else {
                                                // Graded (grade-to-pass is not set).
                                                $simplegradebook[$key]['grade'][$i][$mod->id] = 'graded_.gif';
                                                $simplegradebook[$key]['avg'][] = array('grade' => $grade[$key]->rawgrade,
                                                    'grademax' => $item->grademax);
                                            }
                                        } else {
                                            $simplegradebook[$key]['grade'][$i][$mod->id] = 'unmarked.gif';
                                            if ($unsubmitted) {
                                                $simplegradebook[$key]['avg'][] = array('grade' => 0, 'grademax' => $item->grademax);
                                            }
                                        }
                                    } else {
                                        $simplegradebook[$key]['grade'][$i][$mod->id] = 'ungraded.gif';
                                        if ($unsubmitted) {
                                            $simplegradebook[$key]['avg'][] = array('grade' => 0, 'grademax' => $item->grademax);
                                        }
                                    }
                                } else if ($modstatus = block_ned_teacher_tools_assignment_status($mod, $key, true)) {

                                    switch ($modstatus) {
                                        case 'submitted':
                                            //if ($grade = $gradefunction($instance, $key)) {
                                            if ($grade = block_ned_teacher_tools_gradebook_grade($item->id, $key)) {
                                                if ($item->gradepass > 0) {
                                                    if ($grade >= $item->gradepass) {
                                                        $simplegradebook[$key]['grade'][$i][$mod->id] = 'marked.gif'; // Passed.
                                                        $simplegradebook[$key]['avg'][] = array('grade' => $grade,
                                                            'grademax' => $item->grademax);
                                                    } else {
                                                        $simplegradebook[$key]['grade'][$i][$mod->id] = 'incomplete.gif'; // Fail.
                                                        $simplegradebook[$key]['avg'][] = array('grade' => $grade,
                                                            'grademax' => $item->grademax);
                                                    }
                                                } else {
                                                    // Graded (grade-to-pass is not set).
                                                    $simplegradebook[$key]['grade'][$i][$mod->id] = 'graded_.gif';
                                                    $simplegradebook[$key]['avg'][] = array('grade' => $grade,
                                                        'grademax' => $item->grademax);
                                                }
                                            }
                                            break;

                                        case 'saved':
                                            $simplegradebook[$key]['grade'][$i][$mod->id] = 'saved.gif';
                                            break;

                                        case 'waitinggrade':
                                            $simplegradebook[$key]['grade'][$i][$mod->id] = 'unmarked.gif';
                                            break;
                                    }
                                } else {
                                    $simplegradebook[$key]['grade'][$i][$mod->id] = 'ungraded.gif';
                                    if ($unsubmitted) {
                                        $simplegradebook[$key]['avg'][] = array('grade' => 0, 'grademax' => $item->grademax);
                                    }
                                }
                            }
                        }
                    }
                }
            } // Each mods.
        }
    }
    $weekactivitycount[$i]['numofweek'] = $numberofitem;
}

$PAGE->set_title(get_string('progressreport', 'block_ned_teacher_tools'));
$PAGE->set_heading($SITE->fullname);

// Print header.
$PAGE->navbar->add(get_string('progressreport', 'block_ned_teacher_tools'), new moodle_url(''));
echo $OUTPUT->header();

// The view options.
$viewopts = array(
    '1' => get_string('yes', 'block_ned_teacher_tools'),
    '0' => get_string('no', 'block_ned_teacher_tools')
);
$urlview = new moodle_url('progress_report.php', array('id' => $courseid, 'group' => $group, 'showsuspendedusers' => $showsuspendedusers));
$select = new single_select($urlview, 'unsubmitted', $viewopts, $unsubmitted, '');
$select->formid = 'fngroup1';
$select->label = get_string('includeunsubmittedactivities', 'block_ned_teacher_tools');
$viewform = '<div class="groupselector">'.$OUTPUT->render($select).'</div>';

// Suspended user filter;
$urlview = new moodle_url('progress_report.php', array('id' => $courseid, 'group' => $group, 'unsubmitted' => $unsubmitted));
$select = new single_select($urlview, 'showsuspendedusers', $viewopts, $showsuspendedusers, '');
$select->formid = 'fngroup2';
$select->label = get_string('showsuspendedusers', 'block_ned_teacher_tools');
$viewform .= '<div class="groupselector">'.$OUTPUT->render($select).'</div>';

// No course average calculation.
$nocorseaveragemsg = '';
if ($gradeitem = $DB->get_record('grade_items', array('courseid' => $courseid, 'itemtype' => 'course'))) {
    if ($gradeitem->gradetype == GRADE_TYPE_NONE) {
        $nocorseaveragemsg = '<div class="course-average-warning"><img class="actionicon" width="16" height="16" alt="" src="'.
            $OUTPUT->pix_url('i/risk_xss', '').'"> '.get_string('nocoursetotal', 'block_fn_mentor').'<div>';
    }
}

echo '<div class="fn-menuwrapper">';
block_ned_teacher_tools_groups_print_course_menu($course, $CFG->wwwroot.'/blocks/ned_teacher_tools/progress_report.php?id='.
    $course->id.'&unsubmitted='.$unsubmitted.'&showsuspendedusers='.$showsuspendedusers, false, true);
echo $viewform;
echo "<div class=\"groupselector\"><img src=\"" . $OUTPUT->pix_url('i/grades') . "\" class=\"icon\" alt=\"\" />" .
    '<a href="' . $CFG->wwwroot . '/grade/report/index.php?id=' . $course->id .
    '&navlevel=top">' . get_string('moodlegradebook', 'block_ned_teacher_tools') . '</a></div>';
echo '</div>';
echo '<div class="tablecontainer">';
echo $nocorseaveragemsg;
// TABLE.
echo "<table id='datatable' class='generaltable simplegradebook tablesorter'>";
// First header row.
echo "<thead>";
echo "<tr>";
echo '<th class="sorter-false {sorter: false} borderless-cell" scope="col" align="center"></th>';
echo '<th class="sorter-false {sorter: false} borderless-cell" scope="col" align="center"></th>';
echo '<th class="sorter-false {sorter: false} borderless-cell" scope="col" style="display: none;"></th>';

$truncateactivitynames = get_config('block_ned_teacher_tools', 'truncateactivitynames');

foreach ($weekactivitycount as $key => $value) {
    if ($value['numofweek']) {
        foreach ($value['mod'] as $index => $imagelink) {
            $displayname = $value['modname'][$index];
            if ($truncateactivitynames) {
                $displayname = substr($value['modname'][$index], 0, (int)$truncateactivitynames);
            }
            $displayname = shorten_text($displayname, 30);
            $formattedactivityname = format_string($displayname, true, array('context' => $context));
            echo '<th class="sorter-false {sorter: false}" scope="col" align="center">'.
                '<span class="completion-activityname">'.
                $formattedactivityname.'</span></th>';;
        }
    }
}
echo "</tr>";
// Second header row(activity icons).
echo "<tr>";
echo "<th class='mod-icon'>".get_string('name', 'block_ned_teacher_tools')."</th>";
echo "<th class='mod-icon'}'>%</th>";
echo "<th class='mod-icon'}' style='display: none;'></th>";
foreach ($weekactivitycount as $key => $value) {
    if ($value['numofweek']) {
        foreach ($value['mod'] as $imagelink) {
            echo '<th class="mod-icon sorter-false {sorter: false}">'.$imagelink.'</th>';
        }
    }
}
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$counter = 0;

$toggleicon = html_writer::img($OUTPUT->pix_url('hightlightoff', 'block_ned_teacher_tools'), '', array('class' => 'row-toggle icon'));
$toggleicon = html_writer::div('', 'row-toggle');

$sql = "SELECT gg.finalgrade,
               gg.rawgrademax 
          FROM {grade_grades} gg
          JOIN {grade_items} gi 
            ON gi.id = gg.itemid
         WHERE gi.courseid = ?
           AND gg.userid = ?
           AND gi.itemtype = ?";

foreach ($simplegradebook as $studentid => $studentreport) {
    $counter++;
    if ($counter % 2 == 0) {
        $studentclass = "even";
    } else {
        $studentclass = "odd";
    }
    if ($studentreport['suspended']) {
        $studentclass .= ' suspended';
    }
    echo '<tr>';
    echo '<td nowrap="nowrap" class="'.$studentclass.' name">'. $toggleicon.
        '<a target="_blank" href="'.$CFG->wwwroot.'/grade/report/user/index.php?userid='.
        $studentid.'&id='.$course->id.'">'.$studentreport['name'].'</a></td>';

    $gradetot = 0;
    $grademaxtot = 0;
    $avg = 0;

    if (isset($studentreport['avg'])) {
        if ($cousegrade = $DB->get_record_sql($sql, array($course->id, $studentid, 'course'))) {
            $avg = ($cousegrade->finalgrade / $cousegrade->rawgrademax) * 100;
            if ( $avg >= 50) {
                echo '<td class="green">'.round($avg, 0).'</td>';
            } else {
                echo '<td class="red">'.round($avg, 0).'</td>';
            }
        } else {
            echo '<td class="red"></td>';
        }
    } else {
        echo '<td class="red"> - </td>';
    }

    echo '<td class="" style="display: none;">'.$studentreport['suspended'].'</td>';

    foreach ($studentreport['grade'] as $sgrades) {
        $userurl = $studentreport['url'];
        foreach ($sgrades as $moduleid => $sgrade) {
            $userurl->param('mid', $moduleid);

            switch ($sgrade) {
                case "marked.gif":
                    $userurl->param('show', 'marked');
                    break;
                case "incomplete.gif":
                    $userurl->param('show', 'marked');
                    break;
                case "graded_.gif":
                    $userurl->param('show', 'marked');
                    break;
                case "ungraded.gif":
                    $userurl->param('show', 'unsubmitted');
                    break;
                case "saved.gif":
                    $userurl->param('show', 'saved');
                    break;
                default:
                    $userurl->param('show', 'unmarked');
            }

            echo '<td class="'.$studentclass.' icon">'.'<a href="'.$userurl->out(false).'"><img src="' . $CFG->wwwroot . '/blocks/ned_teacher_tools/pix/'. $sgrade.'" height="16" width="16" alt=""></a></td>';
        }
    }
    echo '</tr>';
}
echo "</tbody>";
echo "</table>";
echo "</div>";
echo '<div style="text-align:center;"><img src="'.$CFG->wwwroot.'/blocks/ned_teacher_tools/pix/gradebook_key.png"></div>';

echo block_ned_teacher_tools_footer();

echo $OUTPUT->footer();
