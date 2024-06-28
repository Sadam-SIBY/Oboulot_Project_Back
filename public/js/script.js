

const navbarButton = document.querySelector("#navbar-button");
const navbar = document.querySelector(".header__menu-nav");

navbarButton.addEventListener('click', handleClickButton);

function handleClickButton() {
  if(navbarButton.classList.contains('open')) {
    navbarButton.classList.remove('open');
    navbar.classList.remove('open');
  }
  else {
    navbarButton.classList.add('open');
    navbar.classList.add('open');
  }
}
