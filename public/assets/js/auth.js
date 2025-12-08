document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login_form');
    const user = document.getElementById('usuario');
    const password = document.getElementById('senha');
    form.onsubmit = () => {
      sessionStorage.setItem('email', user.value);
      sessionStorage.setItem('password', password.value);
    }
  }
)
