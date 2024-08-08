<?php

require_once('../../config.php');
require_login();

$context = context_system::instance();
$pagetype = "create_survey_category";

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/create_survey_category.php'));
$PAGE->set_title(get_string('createsurveycategory', 'local_moodle_survey'));
$PAGE->set_heading(get_string('surveycategorypagetitle', 'local_moodle_survey'));
echo $OUTPUT->header();
$deleteurl = new \moodle_url('/local/moodle_survey/pix/delete-icon.svg');

$categoryoptions['all'] = 'Select Category';

echo html_writer::start_tag('form', ['method' => 'get', 'action' => $PAGE->url, 'id' => 'filter-form']);
    echo html_writer::start_div('filter-form d-flex justify-content-around');
        echo html_writer::empty_tag('input', ['type' => 'date', 'name' => 'createdon', 'placeholder' => get_string('createdat', 'local_moodle_survey'), 'class' => 'date-input']);
        echo html_writer::empty_tag('input', ['type' => 'text', 'name' => 'search', 'value' => $search, 'placeholder' => get_string('search', 'local_moodle_survey'), 'class' => 'search-input']);
    echo html_writer::end_div();
echo html_writer::end_tag('form');

$table = new html_table();
$dbhelper = new \local_moodle_survey\model\survey();
$surveycategories = $dbhelper->get_all_survey_categories();
$table->head = [
    get_string('category', 'local_moodle_survey'),
    get_string('createdon', 'local_moodle_survey'),
    get_string('action', 'local_moodle_survey'),
];

foreach ($surveycategories as $category) {
    $table->data[] = [
        html_writer::link(new moodle_url('/local/moodle_survey/create_survey.php', ['category' => $category->id]),  $category->label),
        date('Y-m-d', strtotime($category->created_at)),
        html_writer::link(new moodle_url($deleteurl), 
            html_writer::tag('img', '', array('src' => $deleteurl, 'alt' => 'Icon', 'class' => 'plus-icon'))
        ),
    ];
}

echo html_writer::table($table);


require_once('includes/footer.php');
