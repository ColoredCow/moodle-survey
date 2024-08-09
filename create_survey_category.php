<?php

require_once('../../config.php');
require_once('components/modal.php');
require_login();

$context = context_system::instance();
$categorytype = required_param('categorytype', PARAM_TEXT);
$pagetype = "create_survey_category";

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/create_survey_category.php'));
$PAGE->set_title(get_string('createsurveycategory', 'local_moodle_survey'));
$filters = get_filters($categorytype);
echo $OUTPUT->header();

$dbhelper = new \local_moodle_survey\model\survey();
$categories = $dbhelper->get_categories_by_filters($filters, $categorytype);
if (strlen($filters['createcategory']) || $filters['categoryid']) {
    redirect(new moodle_url('/local/moodle_survey/create_survey_category.php', ['categorytype' => $categorytype]));
}
echo generate_page_header($categorytype, $filters);
echo generate_filter_form($filters);
echo generate_survey_table($filters, $categorytype, $categories);
echo add_dynamic_form_script();

require_once('includes/footer.php');

/**
 * Generates the page header including the create survey button and heading.
 *
 * @return string HTML content for the page header.
 */
function generate_page_header($categorytype, $filters) {
    global $PAGE;
    $plusicon = new moodle_url('/local/moodle_survey/pix/plus-icon.svg');
    $createbutton = html_writer::div(
        html_writer::link(
            '#',
            html_writer::tag('img', '', ['src' => $plusicon, 'alt' => 'Icon', 'class' => 'plus-icon']) . ' ' . get_string('createsurvey', 'local_moodle_survey'),
            ['class' => 'create-survey-button', 'id' => 'open-modal']
        ),
        'create-survey-button-container'
    );
    if($categorytype == get_string('survey', 'local_moodle_survey')) {
        $addcategorytitle = get_string('addsurveycategory', 'local_moodle_survey');
        $categoryheading = 'Surveys / '. get_string('surveycategorypagetitle', 'local_moodle_survey');
    } else {
        $addcategorytitle = get_string('addquestioncategory', 'local_moodle_survey');
        $categoryheading = 'Surveys / '. get_string('questioncategorypagetitle', 'local_moodle_survey');
    }
    
    $heading = html_writer::tag('span', $categoryheading, ['class' => 'survey-name']);
    $content = $heading . ' ' . $createbutton;
    $modallabel = $addcategorytitle;
    $modalurl = new moodle_url($PAGE->url, ['categorytype' => $categorytype]);
    $modaldescription = html_writer::start_tag('form', ['method' => 'get', 'action' => $modalurl, 'id' => 'filter-form']) . html_writer::div(
        html_writer::empty_tag('input', [
            'type' => 'text',
            'name' => 'createcategory',
            'placeholder' => $addcategorytitle,
            'class' => 'add-category-field'
        ]) . 
        html_writer::empty_tag('input', [
            'type' => 'submit',
            'value' => 'Add',
            'class' => 'custom-action-btn add-category-btn'
        ])
        , 'add-category-form'
    );
    $modal = generate_modal($modallabel, $modaldescription);
    return html_writer::tag('div', $content . $modal, ['class' => 'survey-header']);
}

/**
 * Generates the filter form for searching and filtering survey categories.
 *
 * @return string HTML content for the filter form.
 */
function generate_filter_form($filters) {
    global $PAGE;

    return html_writer::start_tag('form', ['method' => 'get', 'action' => $PAGE->url, 'id' => 'filter-form']) .
            html_writer::start_div('filter-form d-flex justify-content-around') .
            html_writer::empty_tag('input', ['type' => 'date', 'name' => 'createdon', 'placeholder' => get_string('createdat', 'local_moodle_survey'), 'class' => 'date-input category-filter']) .
            html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'categorytype', 'value' => $filters['categorytype']]) .
            html_writer::empty_tag('input', ['type' => 'text', 'name' => 'search', 'value' => $filters['search'], 'placeholder' => get_string('search', 'local_moodle_survey'), 'class' => 'search-input category-filter']) .
            html_writer::end_div() .
            html_writer::end_tag('form');
}

/**
 * Generates the survey table with categories and actions.
 *
 * @return string HTML content for the survey table.
 */
function generate_survey_table($filters, $categorytype, $categories) {
    global $PAGE;
    $table = new html_table();
    $deleteicon = new moodle_url('/local/moodle_survey/pix/delete-icon.svg');
    
    if(sizeof($categories) > 0) {
        $table->head = [
            get_string('category', 'local_moodle_survey'),
            get_string('createdon', 'local_moodle_survey'),
            get_string('action', 'local_moodle_survey'),
        ];
        
        foreach ($categories as $category) {
            $deleteurl = new moodle_url($PAGE->url, ['categoryid' => $category->id, 'categorytype' => $categorytype]);
            $table->data[] = [
                html_writer::link(new moodle_url('/local/moodle_survey/create_survey.php', ['category' => $category->id]),  $category->label),
                date('Y-m-d', strtotime($category->created_at)),
                html_writer::link($deleteurl, 
                    html_writer::tag('img', '', ['src' => $deleteicon, 'alt' => 'Icon', 'class' => 'plus-icon'])
                ),
            ];
        }
    } else {
        echo html_writer::tag('div', get_string('nocategoryfound', 'local_moodle_survey'), ['class' => 'alert alert-info']);
    }

    return html_writer::table($table);
}

/**
 * Retrieves the filter parameters from the request.
 *
 * @return array
 */
function get_filters($categorytype) {
    $search = optional_param('search', '', PARAM_RAW_TRIMMED);
    $createdon = optional_param('createdon', '', PARAM_RAW_TRIMMED);
    $createcategory = optional_param('createcategory', '', PARAM_RAW_TRIMMED);
    $categoryid = optional_param('categoryid', '', PARAM_INT);

    return [
        'search' => $search,
        'createdon' => $createdon,
        'categorytype' => $categorytype,
        'createcategory' => $createcategory,
        'categoryid' => $categoryid
    ];
}

function add_dynamic_form_script() {
    return html_writer::script("
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.querySelector('.date-input');
            var form = document.getElementById('filter-form');

            function submitForm() {
                form.submit();
            }

            if (dateInput) {
                dateInput.addEventListener('change', submitForm);
            }
        });
    ");
}
