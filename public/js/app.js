const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

function updateTitle() {
  const loginForm = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');

  if (loginForm.classList.contains('active')) {
      document.title = 'KMJP | LOGIN';
  } else if (registerForm.classList.contains('active')) {
      document.title = 'KMJP | REGISTER';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const signUpBtn = document.getElementById('sign-up-btn');
  const signInBtn = document.getElementById('sign-in-btn');
  updateTitle();

  if (signUpBtn) {
      signUpBtn.addEventListener('click', () => {
          document.getElementById('register-form').classList.add('active');
          document.getElementById('login-form').classList.remove('active');
          updateTitle();
      });
  }

  if (signInBtn) {
      signInBtn.addEventListener('click', () => {
          document.getElementById('login-form').classList.add('active');
          document.getElementById('register-form').classList.remove('active');
          updateTitle();
      });
  }
});
