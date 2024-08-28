<?php
    $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
?>

<h3>Assigning - <?php echo $survey->name; ?></h3>

<div class="accordion my-5 active">
    <div class="accordion-header accordion-header-section">
        <img src="<?php echo $iconurl; ?>" alt="Icon" class="accordion-icon">
        <h5>Target Audience</h5>
    </div>
    <div class="accordion-body pl-5">
        <div class="row">
        <?php
            foreach (json_decode($schoolsurvey->target_audience) as $audience) {
                echo '<div class="form-check mr-3">
                        <input class="form-check-input" type="checkbox" checked disabled>
                        <label class="form-check-label" for="flexCheckDefault">' . $audience . '</label></div>';
            }
        ?>
        </div>
    </div>
</div>

<form method="POST" class="needs-validation" novalidate>
    <div class="accordion my-5 active">
        <div class="accordion-header accordion-header-section">
            <img src="<?php echo $iconurl; ?>" alt="Icon" class="accordion-icon">
            <h5>Assign To</h5>
        </div>
        <div class="accordion-body pl-5">
            <div class="row">
                <?php
                    foreach (json_decode($schoolsurvey->target_audience) as $audience) {
                        $attribute = in_array($audience, json_decode($schoolsurvey->assigned_to ?? "[]")) ? 'checked' : '';
                        echo '<div class="form-check mr-3">
                                <input class="form-check-input" name="assign_to[]" value="' . $audience . '" type="checkbox" ' . $attribute .'> 
                                <label class="form-check-label" for="flexCheckDefault">' . $audience . '</label></div>';
                    }
                ?>
            </div>
        </div>
    </div>

    <?php
        $targetAudience = json_decode($schoolsurvey->target_audience);
        if (in_array('teacher', $targetAudience)) {
            ?>
                <div class="accordion my-5 active">
                    <div class="accordion-header accordion-header-section">
                        <img src="<?php echo $iconurl; ?>" alt="Icon" class="accordion-icon">
                        <h5>Teacher details</h5>
                    </div>
                    <div class="accordion-body pl-5">
                        <div class="row">
                            <select class="form-control" name="assign_to_teachers[]" multiple required>
                                <?php
                                    $selectedGrades = isset($schoolsurvey->teacher_grade) ? json_decode($schoolsurvey->teacher_grade) : [];
                                    for ($grade = 1; $grade <= 12; $grade++) {
                                        $selected = in_array($grade, $selectedGrades) ? 'selected' : '';
                                        echo '<option value="' . $grade . '" ' . $selected . '>Grade ' . $grade . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            <?php
        }
    ?>

    <?php
        $targetAudience = json_decode($schoolsurvey->target_audience);
        if (in_array('student', $targetAudience)) {
        ?>
            <div class="accordion my-5 active">
                <div class="accordion-header accordion-header-section">
                    <img src="<?php echo $iconurl; ?>" alt="Icon" class="accordion-icon">
                    <h5>Student details</h5>
                </div>
                <div class="accordion-body pl-5">
                    <div class="row">
                        <select class="form-control" name="assign_to_students[]" multiple required>
                            <?php
                                $selectedGrades = isset($schoolsurvey->student_grade) ? json_decode($schoolsurvey->student_grade) : [];
                                for ($grade = 1; $grade <= 12; $grade++) {
                                    $selected = in_array($grade, $selectedGrades) ? 'selected' : '';
                                    echo '<option value="' . $grade . '" ' . $selected . '>Grade ' . $grade . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        <?php
        }
    ?>
        
    <div class="custom-form-action-buttons">
        <button name="pressed_button" value="cancel" type="submit" class="custom-question-form-cancel-button">Cancel</button>
        <button name="pressed_button" value="save" type="submit" class="custom-question-form-submit-button ml-4">Assign</button>
    </div>
</form>