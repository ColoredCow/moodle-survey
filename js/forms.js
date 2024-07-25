function addQuestionField() {
    // Logic to clone the last question and score fields, update indices, and append to the form
}

document.addEventListener('DOMContentLoaded', function() {
    handleAccordion();
});

function handleAccordion() {
    var accHeaders = document.querySelectorAll('.accordion-header');

    accHeaders.forEach(function(header) {
        header.addEventListener('click', function() {
            var accBody = this.nextElementSibling;
            if (accBody.style.display === 'none') {
                accBody.style.display = 'block';
            } else {
                accBody.style.display = 'none';
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    let questionIndex = 1;
    handleAccordion();

    document.getElementById('add-new-question-button').addEventListener('click', function() {
        questionIndex++;

        const template = document.getElementById('question-template');
        const newQuestion = template.cloneNode(true);

        newQuestion.querySelector('#question-number').textContent = questionIndex;
        newQuestion.setAttribute('data-question-number', questionIndex);

        document.getElementById('accordion').appendChild(newQuestion);
    });
});
