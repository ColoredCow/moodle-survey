

const addEventListenerForAccordion = (accordionHeaders) => {
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

const handleAccordion = () => {
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    addEventListenerForAccordion(accordionHeaders)
}

handleAccordion();


document.addEventListener('DOMContentLoaded', function() {
  const addNewQuestionButton = document.getElementById('add-new-question-button');
  const questionContainer = document.getElementById('question-item-section');

  const questionTemplate = document.getElementById('question-template').innerHTML;
  addNewQuestionButton.addEventListener('click', function() {
    let questionNumber = document.querySelectorAll('.question-number').length;
    newQuestionHtml = questionTemplate.replace(/__INDEX__/g, questionNumber-1).replace(/__POSITION__/g, questionNumber)
    questionContainer.insertAdjacentHTML('beforeend', newQuestionHtml);
    
    const addNewQuestionOptionButtons = questionContainer.getElementsByClassName('add-new-question-option-button');
    Array.from(addNewQuestionOptionButtons).forEach(function(button) {
      button.addEventListener('click', addNewQuestionOption);
    });
    console.log('accordion-' + (questionNumber - 1))
    const accordion = document.getElementById('accordion-' + (questionNumber-1));
    const accordionHeaders = accordion.querySelectorAll('.accordion-header');

    addEventListenerForAccordion(accordionHeaders)
  });

  const addNewQuestionOptionButtons = document.getElementsByClassName('add-new-question-option-button');
  Array.from(addNewQuestionOptionButtons).forEach(function(button) {
    button.addEventListener('click', addNewQuestionOption);
  });
});

const addNewQuestionOption = (e) => {
  const questionIndex = e.target.getAttribute("data-id")
  console.log("questionIndex", questionIndex)
  const optionContainer = document.getElementById('option-container-' + questionIndex)
  console.log("optionContainer", optionContainer)
  const existingOptionsCount = optionContainer.querySelectorAll('.question-option').length;
  console.log(existingOptionsCount);
  const optionTemplate = document.getElementById('question-option-template').innerHTML;
  newOptionHtml = optionTemplate.replace(/__OPTIONINDEX__/g, existingOptionsCount).replace(/__INDEX__/g, questionIndex)
  optionContainer.insertAdjacentHTML('beforeend', newOptionHtml);
}

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
  .forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()