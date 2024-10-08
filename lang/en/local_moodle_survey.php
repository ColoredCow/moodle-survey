<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_moodle_survey
 * @category    string
 * @copyright   2024 ColoredCow 
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Saishiko Surveys';
$string['managesurvey'] = 'Surveys';
$string['createsurvey'] = 'Add New Survey';
$string['createcategory'] = 'Add New Category';
$string['createsurveycategory'] = 'Survey Categories';
$string['newquestioncategory'] = 'Question Categories';
$string['surveycategorypagetitle'] = 'Survey categories';
$string['questioncategorypagetitle'] = 'Question categories';
$string['surveyname'] = 'Name';
$string['surveynameplaceholder'] = 'Enter Survey Name';
$string['surveycategory'] = 'Category';
$string['createdon'] = 'Created ON';
$string['schools'] = 'Schools';
$string['responses'] = 'Responses';
$string['surveytargetaudience'] = 'Target Audience';
$string['surveydescription'] = 'Survey Description';
$string['surveydescriptionplaceholder'] = 'Write what is this survey about';
$string['nosurveysfound'] = 'No surveys found.';
$string['nocategoryfound'] = 'No categories found.';
$string['actions'] = 'Actions';
$string['edit'] = 'Edit';
$string['delete'] = 'Delete';
$string['editsurvey'] = 'Edit Survey';
$string['surveyupdated'] = 'Survey updated successfully';
$string['surveydeleted'] = 'Survey deleted successfully';
$string['confirmdelete'] = 'Are you sure you want to delete this survey?';
$string['nosurveysfound'] = 'No surveys found.';
$string['generaldetails'] = 'General Details';
$string['surveydetails'] = 'Survey Details';
$string['questionsscores'] = 'Questions & Scores';
$string['interpretations'] = 'Interpretations';
$string['validity'] = 'Validity';
$string['audienceaccess'] = 'Audience & Access';
$string['questions'] = 'Questions';
$string['scores'] = 'Scores';
$string['audience'] = 'Audience';
$string['access'] = 'Access';
$string['surveystatuslabel'] = 'Status';
$string['active'] = 'Active';
$string['inactive'] = 'Inactive';
$string['filter'] = 'Filter';
$string['search'] = 'Search';
$string['all'] = 'Select';
$string['live'] = 'Live';
$string['completed'] = 'Completed';
$string['draft'] = 'Draft';
$string['published'] = 'Published';
$string['createdat'] = 'Created At';
$string['newsurveycategory'] = 'Add New Survey Category';
$string['submit'] = 'Save & Continue';
$string['questionlabel'] = 'Question';
$string['questioncategorylabel'] = 'Question Category';
$string['questionplaceholder'] = 'ex : How frequently do you need guidance...';
$string['addnewscorebutton'] = 'Add new score and associate option';
$string['addnewscorebuttonid'] = 'add-new-score-button';
$string['score'] = 'Score';
$string['associatedoption'] = 'Associated option';
$string['scorefrom'] = 'From';
$string['scoreto'] = 'To';
$string['interpretedas'] = 'Interpreted as';
$string['interpretedasplaceholder'] = 'ex: never';
$string['questioncategory'] = 'Question Category';
$string['addnewcategory'] = 'Add New Category';
$string['addnewcategoryid'] = 'add-new-category';
$string['addnewquestion'] = 'Add a new questions';
$string['newrangeandinterpretation'] = 'Add new range and interpretation';
$string['surveyvalidityheading'] = 'Duration of collecting responses';
$string['surveyvaliditystartdatelabel'] = 'Start Date';
$string['surveyvalidityenddatelabel'] = 'End Date';
$string['publishsurveybtn'] = 'Save & Publish';
$string['targetaudience'] = 'TARGET AUDIENCE';
$string['accesstoresponse'] = 'ACCESS TO RESPONSES';
$string['assigntoschool'] = 'ASSIGN TO SCHOOL';
$string['targetaudiencevalues'] = [
    'student' => 'Students',
    'teacher' => 'Teachers',
];
$string['accesstoresponsevalues'] = [
    'principal' => 'Principal',
    'counsellor' => 'Counsellor',
];
$string['assigntoschools'] = [
    'school'  => 'School',
];
$string['suveyparticipationcontent'] = 'Thank you for taking the time to participate in our survey. Your feedback is valuable and will help us improve our services.';
$string['moodle_survey:create-surveys'] = 'Can create surveys';
$string['moodle_survey:view-surveys'] = 'Can view list of surveys';
$string['moodle_survey:create-survey-category'] = 'Can create survey category';
$string['moodle_survey:view-survey-category'] = 'Can view list of survey category';
$string['moodle_survey:create-question-category'] = 'Can create question category';
$string['moodle_survey:view-question-category'] = 'Can view list of question category';
$string['moodle_survey:create-school'] = 'Can create schools';
$string['moodle_survey:view-school'] = 'Can view list of schools';
$string['moodle_survey:create-teacher'] = 'Can create teachers';
$string['moodle_survey:view-teacher'] = 'Can view list of teachers';
$string['moodle_survey:create-student'] = 'Can create students';
$string['moodle_survey:view-student'] = 'Can view list of students';
$string['moodle_survey:assign-course-to-school'] = 'Can assign course to schools';
$string['moodle_survey:assign-course-to-user'] = 'Can assign course to users';
$string['moodle_survey:create-courses'] = 'Can create Saishiko Course';
$string['moodle_survey:view-principal'] = 'Can view list of principal';
$string['moodle_survey:create-principal'] = 'Can create principal';
$string['moodle_survey:view-school-admin'] = 'Can view list of school admin';
$string['moodle_survey:create-school-admin'] = 'Can create school admin';
$string['moodle_survey:view-counsellor'] = 'Can view list of counsellor';
$string['moodle_survey:create-counsellor'] = 'Can create counsellor';
$string['moodle_survey:view-courses'] = 'Can view list of Saishiko Course';
$string['moodle_survey:create-moocs'] = 'Can create MOOCs';
$string['moodle_survey:view-moocs'] = 'Can view list of MOOCs';
$string['moodle_survey:create-mooc-category'] = 'Can create MOOCs category';
$string['moodle_survey:view-mooc-category'] = 'Can view list of MOOCs category';
$string['moodle_survey:create-course-category'] = 'Can create course category';
$string['moodle_survey:view-course-category'] = 'Can view list of course category';
$string['moodle_survey:view-individual-survey-insights'] = 'Can view list of individual survey insights';
$string['moodle_survey:view-quick-access-buttons'] = 'Can view quick access buttons';
$string['moodle_survey:view-my-courses'] = 'Can view associated courses';
$string['moodle_survey:can-assign-survey-to-users'] = 'Can assign survey to users of the school';
$string['moodle_survey:view-survey-analysis'] = 'Can view survey analysis';
$string['sruveyacceptancetext'] = 'I agree to participate in the survey enrolled on this platform';
$string['whatsurveyabout'] = 'What is this survey about?';
$string['instructionforfiillingsurveyheading'] = 'Instructions for Filling the Survey';
$string['instructionforfiillingsurvey'] = [
    'Read Each Question Carefully: Take your time to understand each question before answering.',
    'Be Honest and Candid: Your genuine responses are crucial for accurate data collection.',
    'Select the Best Option: Choose the option that best represents your opinion or experience.',
    'Provide Detailed Responses: For open-ended questions, feel free to share as much detail as possible.',
    'Confidentiality: Your responses will remain confidential and will be used solely for research purposes.'
];
$string['saveandsubmit'] = 'Save & Submit';
$string['yourscore'] = 'Your Score';
$string['scoreinterpretation'] = 'Score Interpretation';
$string['surveylandingtitle'] = 'Welcome To ';
$string['category'] = 'Category';
$string['action'] = 'Action';
$string['survey'] = 'survey';
$string['question'] = 'question';
$string['addsurveycategory'] = 'Add Survey Category';
$string['addquestioncategory'] = 'Add Question Category';
$string['competency'] = 'Competency';
$string['noquestioncategorychooses'] = 'No Question Catgory Chooses';
$string['fillgeneraldetailsform'] = 'Fill the General Details Form';
$string['chartlabels'] =  [
        [
            "label" => "Underdeveloped",
        ],
        [
            "label" => "Developing",
        ],
        [
            "label" => "Progressing",
        ],
        [
            "label" => "Remarkable",
        ]
    ];
$string['chartcolorset'] = ['#F47A29', '#FFB685', '#FFF0E6', '#FFF'];
$string['surveyinsighttypes'] = [
    'teacher' => 'Teachers Insights',
    'student' => 'Student Insights',
];
$string['surveystatus'] = [
    "all" => "Select Status",
    'Live' => "Live",
    "Draft" => "Draft",
    "Completed" => "Completed",
];