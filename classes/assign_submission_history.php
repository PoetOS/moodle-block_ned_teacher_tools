<?php
namespace block_ned_teacher_tools;

defined('MOODLE_INTERNAL') || die();

class assign_submission_history implements renderable {

    public $allsubmissions = array();
    public $allgrades = array();
    public $submissionnum = 1;
    public $maxsubmissionnum = 1;
    public $submissionplugins = array();
    public $feedbackplugins = array();
    public $coursemoduleid = 0;
    public $returnaction = '';
    public $returnparams = array();

    public function __construct($allsubmissions, $allgrades, $submissionnum, $maxsubmissionnum, $submissionplugins,
                                $feedbackplugins, $coursemoduleid, $returnaction, $returnparams) {
        $this->allsubmissions = $allsubmissions;
        $this->allgrades = $allgrades;
        $this->submissionnum = $submissionnum;
        $this->maxsubmissionnum = $maxsubmissionnum;
        $this->submissionplugins = $submissionplugins;
        $this->feedbackplugins = $feedbackplugins;
        $this->coursemoduleid = $coursemoduleid;
        $this->returnaction = $returnaction;
        $this->returnparams = $returnparams;
    }
}