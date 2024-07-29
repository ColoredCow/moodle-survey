<?php
    echo html_writer::start_tag('div', array('class' => 'survey-submission-container'));

        echo html_writer::tag('p', 'Thank you for taking the time to participate in our survey. Your feedback is valuable and will help us improve our services.', array('class' => 'survey-thanks-content'));

        echo html_writer::start_tag('div', array('class' => 'survey-about-container'));
            echo html_writer::tag('h3', 'What is this survey about?', array('class' => 'survey-about-heading'));
            echo html_writer::tag('p', 'This survey aims to gather your insights and opinions on different modes of learning. We want to understand your experiences and preferences with various learning methods to better cater to your needs.', array('class' => 'survey-about-container'));
        echo html_writer::end_tag('div');

        echo html_writer::start_tag('div', array('class' => 'survey-instruction-container'));
            echo html_writer::tag('h3', 'Instructions for Filling the Survey', array('class' => 'survey-instruction-heading'));
            echo html_writer::start_tag('ol');
                echo html_writer::tag('li', 'Read Each Question Carefully: Take your time to understand each question before answering.');
                echo html_writer::tag('li', 'Be Honest and Candid: Your genuine responses are crucial for accurate data collection.');
                echo html_writer::tag('li', 'Select the Best Option: Choose the option that best represents your opinion or experience.');
                echo html_writer::tag('li', 'Provide Detailed Responses: For open-ended questions, feel free to share as much detail as possible.');
                echo html_writer::tag('li', 'Confidentiality: Your responses will remain confidential and will be used solely for research purposes.');
            echo html_writer::end_tag('ol');
        echo html_writer::end_tag('div');

        echo html_writer::start_tag('div', array('class' => 'survey-participation-confirmation'));
            echo html_writer::checkbox('survey-participation', $surveyparticipation, null, 'I agree to participate in the survey as a student enrolled on this platform');
        echo html_writer::end_tag('div');

    echo html_writer::end_tag('div');
?>

<!-- Action Buttons -->
<?php
$submitlabel = get_string('submit', 'local_moodle_survey');
$cancellabel = get_string('cancel');

echo html_writer::div(
    html_writer::link(
        $createsurveycategoryurl,
        $cancellabel,
        array('class' => 'custom-declined-button custom-action-btn')
    ) . html_writer::link(
        $createsurveycategoryurl,
        $submitlabel,
        array('class' => 'custom-continue-button custom-action-btn')
    ),
    'custom-form-action-buttons'
);
?>

