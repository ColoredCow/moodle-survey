M.util.js_pending('local_moodle_survey/forms');
console.log('JavaScript file loaded.');

function handleAccordion() {
    const accordionHeaders = document.querySelectorAll('.accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const currentlyActive = document.querySelector('.accordion-item.active');
            
            if (currentlyActive && currentlyActive !== this.parentElement) {
                currentlyActive.classList.remove('active');
                currentlyActive.querySelector('.accordion-body').style.display = 'none';
            }
            
            const isActive = this.parentElement.classList.contains('active');
            this.parentElement.classList.toggle('active', !isActive);
            this.nextElementSibling.style.display = isActive ? 'none' : 'block';
        });
    });
}

handleAccordion();


// document.addEventListener('DOMContentLoaded', function() {
//     let questionIndex = 0;

//     document.getElementById('add-new-question-button').addEventListener('click', function() {
//         questionIndex++;

//         const template = document.getElementById('question-template');
//         const newQuestion = template.cloneNode(true);

//         // Update the IDs and names of the cloned elements
//         newQuestion.querySelector('#question-number').textContent = questionIndex + 1;
//         newQuestion.setAttribute('data-question-number', questionIndex + 1);

//         // Update input names to be unique
//         newQuestion.querySelectorAll('input, select').forEach((element) => {
//             const name = element.name.replace(/\[\d+\]/, `[${questionIndex}]`);
//             element.name = name;
//         });

//         // Append the new question
//         document.getElementById('accordion').appendChild(newQuestion);

//         // Re-initialize accordion functionality
//         handleAccordion();
//     });
// });

// YUI().use('node', 'event', 'moodle-form-changechecker', function(Y) {
//     document.addEventListener('DOMContentLoaded', function() {
//         console.log('DOM fully loaded and parsed.');
//         const addNewQuestionButton = document.getElementById('add-new-question-button');
//         if (!addNewQuestionButton) {
//             console.log('Add New Question button not found.');
//             return;
//         }
//         console.log('Add New Question button found.');
//         const questionContainer = document.getElementById('accordion');

//         if (!questionContainer) {
//             console.log('Question container not found.');
//             return;
//         }
//         console.log('Question container found.');

//         const questionTemplate = document.getElementById('question-template').innerHTML;
//         let questionIndex = document.querySelectorAll('.question-number').length;
//         addNewQuestionButton.addEventListener('click', function() {
//             console.log('Add New Question button clicked.');
//             const newQuestionHtml = questionTemplate.replace(/TEMPLATE_INDEX/g, questionIndex);
//             questionContainer.insertAdjacentHTML('beforeend', newQuestionHtml);
//             questionIndex++;
//             console.log('New question added. Current index:', questionIndex);

//             // Reinitialize form validation rules
//             M.form.changechecker.reset_form_dirty_state();
//             M.form.applyAutoCompleteEnhancements();
//             console.log('Form validation rules reinitialized.');
//         });
//     });
//     M.util.js_complete('local_moodle_survey/forms');
// });

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed.');
    const addNewQuestionButton = document.getElementById('add-new-question-button');
    if (!addNewQuestionButton) {
        console.log('Add New Question button not found.');
        return;
    }
    console.log('Add New Question button found.');
    const questionContainer = document.getElementById('accordion');

    if (!questionContainer) {
        console.log('Question container not found.');
        return;
    }
    console.log('Question container found.');

    const questionTemplate = document.getElementById('question-template').innerHTML;
    let questionIndex = document.querySelectorAll('.question-number').length;
    addNewQuestionButton.addEventListener('click', function() {
        console.log('Add New Question button clicked.');
        const newQuestionHtml = questionTemplate.replace(/TEMPLATE_INDEX/g, questionIndex);
        questionContainer.insertAdjacentHTML('beforeend', newQuestionHtml);
        questionIndex++;
        console.log('New question added. Current index:', questionIndex);
    });
});