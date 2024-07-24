<?php
    // Define icon URLs
    $iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
    $plusicon = new moodle_url('/local/moodle_survey/pix/plus-icon.svg');
?>

<div id="audience">
    <?php
        $mform = new \local_moodle_survey\form\create\audience_access_form();
        echo $mform->display();
    ?>
</div>
