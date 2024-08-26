<?php
    $dbhelper = new \local_moodle_survey\model\survey();
    $categories = $dbhelper->get_all_survey_categories();
    $surveystatusoptions = $dbhelper->get_all_survey_status();
    $createurl = new moodle_url('/local/moodle_survey/create_survey.php');
    $createsurveycategoryurl = new moodle_url('/local/moodle_survey/create_category.php', ['categorytype' => get_string('survey', 'local_moodle_survey')]);
    $createquestioncategoryurl = new moodle_url('/local/moodle_survey/create_category.php', ['categorytype' => get_string('question', 'local_moodle_survey')]);
    $iconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
    $createbutton = html_writer::div(
        html_writer::link(
            $createquestioncategoryurl,
            get_string('newquestioncategory', 'local_moodle_survey'),
            array('class' => 'create-button')
        ) .
        html_writer::link(
            $createsurveycategoryurl,
            get_string('createsurveycategory', 'local_moodle_survey'),
            array('class' => 'create-button')
        ) . html_writer::link(
            $createurl,
            html_writer::tag('img', '', array('src' => $iconurl, 'alt' => 'Icon', 'class' => 'plus-icon')) . ' ' . get_string('createsurvey', 'local_moodle_survey'),
            array('class' => 'create-button')
        ),
        'create-button-container'
    );
    $heading = html_writer::tag('span', get_string('managesurvey', 'local_moodle_survey'), ['class' => 'page-title']);
    $content = $heading;
    if (has_capability('local/moodle_survey:create-surveys', context_system::instance())) {
        $content .= ' ' . $createbutton;
    }
    
    echo html_writer::tag('div', $content, ['class' => 'survey-header']);

    // Filter form
    $categoryoptions['all'] = 'Select Category';
    foreach ($categories as $key => $category) {
        $categoryoptions[$category->id] = $category->label;
    }
    echo html_writer::start_tag('form', ['method' => 'get', 'action' => $PAGE->url, 'id' => 'filter-form']);
    echo html_writer::start_div('filter-form d-flex justify-content-between');
    echo html_writer::select($surveystatusoptions, 'status', $filters['status'], null, ['class' => 'status-select', 'id' => 'status-select']);
    echo html_writer::empty_tag('input', ['type' => 'date', 'name' => 'createdon', 'value' => $filters['createdon'], 'placeholder' => get_string('createdat', 'local_moodle_survey'), 'class' => 'date-input']);
    echo html_writer::select($categoryoptions, 'category', $filters['surveycategory'], null, ['class' => 'status-select', 'id' => 'category-select']);

    echo html_writer::empty_tag('input', ['type' => 'text', 'name' => 'search', 'value' => $filters['search'], 'placeholder' => get_string('search', 'local_moodle_survey'), 'class' => 'search-input']);

    echo html_writer::end_div();
    echo html_writer::end_tag('form');

    // JavaScript for automatic form submission
    echo html_writer::script("
        document.addEventListener('DOMContentLoaded', function() {
            var statusSelect = document.getElementById('status-select');
            var categorySelect = document.getElementById('category-select');
            var dateInput = document.querySelector('.date-input');
            var form = document.getElementById('filter-form');

            function submitForm() {
                form.submit();
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', submitForm);
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', submitForm);
            }

            if (dateInput) {
                dateInput.addEventListener('change', submitForm);
            }
        });
    ");
?>
