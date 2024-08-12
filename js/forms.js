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
    const accordion = document.getElementById('accordion-' + (questionNumber-1));
    const accordionHeaders = accordion.querySelectorAll('.accordion-header');

    addEventListenerForAccordion(accordionHeaders)
  });

  const addNewQuestionOptionButtons = document.getElementsByClassName('add-new-question-option-button');
  Array.from(addNewQuestionOptionButtons).forEach(function(button) {
    button.addEventListener('click', addNewQuestionOption);
  });
  
  const addNewInterpretationButtons = document.getElementsByClassName('add-new-interpretation-button');
  Array.from(addNewInterpretationButtons).forEach(function(button) {
    button.addEventListener('click', addNewInterpretation);
  });

  let questionsCount = document.querySelectorAll('.question-number').length;
  if (questionsCount == 1) {
    addNewQuestionButton.click();
  }
  
  let categories = document.querySelectorAll('.question-category-number');
  Array.from(categories).forEach(function(categoryElement) {
    const categoryIndex = categoryElement.getAttribute('data-id')
    const interpretationContainer = document.getElementById('interpretation-container-' + categoryIndex);
    const interpretationCount = interpretationContainer.querySelectorAll('.question-interpretation').length;
    const addNewInterpretationButton = document.getElementById('add-new-interpretation-button-' + categoryIndex);
    if (interpretationCount == 0) {
      addNewInterpretationButton.click();
    }
  })
});

const addNewQuestionOption = (e) => {
  const questionIndex = e.target.getAttribute("data-id")
  const optionContainer = document.getElementById('option-container-' + questionIndex)
  const existingOptionsCount = optionContainer.querySelectorAll('.question-option').length;
  const optionTemplate = document.getElementById('question-option-template').innerHTML;
  newOptionHtml = optionTemplate.replace(/__OPTIONINDEX__/g, existingOptionsCount).replace(/__INDEX__/g, questionIndex)
  optionContainer.insertAdjacentHTML('beforeend', newOptionHtml);
}


const addNewInterpretation = (e) => {
  const interpretationContainerIndex = e.target.getAttribute("data-id")
  const interpretationContainer = document.getElementById('interpretation-container-' + interpretationContainerIndex)
  const existingInterpretationCount = interpretationContainer.querySelectorAll('.question-interpretation').length;
  const interpretationTemplate = document.getElementById('interpretation-template').innerHTML;
  newInterpretationHtml = interpretationTemplate.replace(/__INTERPRETATIONINDEX__/g, existingInterpretationCount).replace(/__INDEX__/g, interpretationContainerIndex)
  interpretationContainer.insertAdjacentHTML('beforeend', newInterpretationHtml);
}

function validateForms() {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');

  forms.forEach(form => {
    form.addEventListener('submit', (event) => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation(); 

      }

      form.classList.add('was-validated');

    });
  });
}

validateForms();