//Contact Form Validation Module (js/contact-validation.js)

document.addEventListener('DOMContentLoaded', () => {

  const contactForm = document.getElementById('contactForm');
  const fullNameInput = document.getElementById('fullName');
  const emailInput = document.getElementById('emailAddr');
  const msgInput = document.getElementById('msgContent');
  const charCounter = document.getElementById('charCounter');
  const contactToastEl = document.getElementById('contactToast');

  if (!contactForm) return;

  // Real-time Email Regex Pattern Validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function validateField(input, isConditionValid) {
    if (isConditionValid) {
      input.classList.remove('is-invalid');
      input.classList.add('is-valid');
      return true;
    } else {
      input.classList.remove('is-valid');
      input.classList.add('is-invalid');
      return false;
    }
  }

  // Real-time Name Input Event
  if (fullNameInput) {
    fullNameInput.addEventListener('input', () => {
      validateField(fullNameInput, fullNameInput.value.trim().length >= 3);
    });
  }

  // Real-time Email Input Event
  if (emailInput) {
    emailInput.addEventListener('input', () => {
      validateField(emailInput, emailRegex.test(emailInput.value.trim()));