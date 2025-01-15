const background = document.querySelector('.background');
let position = 0;

function animateBackground() {
    position -= 0.5; // Vitesse de déplacement
    background.style.backgroundPosition = `${position}px 0`;
    requestAnimationFrame(animateBackground);
}

animateBackground();
