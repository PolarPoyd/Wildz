// JavaScript code for hamburger menu
const burger = document.querySelector('.burger');
const navHome = document.querySelector('.navHome');

burger.addEventListener('click', () => {
    navHome.classList.toggle('responsive');
});