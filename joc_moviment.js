document.addEventListener('DOMContentLoaded', () => {
    const arrows = document.querySelectorAll('.fletxes .imgfletxa');
    const pointsDisplay = document.querySelector('.punts a');
    const progressBar = document.getElementById('file');
    const audio = document.getElementById('audio'); // Obtener el elemento de audio directamente
    let score = 0;
    const music = "<?php echo $music; ?>";
    const songTitle = "<?php echo $title; ?>";

    let currentArrowIndex = -1;
    let gameEnded = false;

    const showRandomArrow = () => {
        if (gameEnded) return;

        arrows.forEach((arrow) => {
            arrow.style.display = 'none';
        });

        currentArrowIndex = Math.floor(Math.random() * arrows.length);
        arrows[currentArrowIndex].style.display = 'block';

        setTimeout(showRandomArrow, 1000);
    };

    const handleKeyPress = (event) => {
        if (gameEnded) return;

        switch (event.key) {
            case 'ArrowLeft':
                if (currentArrowIndex === 0) {
                    score += 100;
                } else {
                    score -= 50;
                }
                break;
            case 'ArrowUp':
                if (currentArrowIndex === 1) {
                    score += 100;
                } else {
                    score -= 50;
                }
                
                break;
            case 'ArrowDown':
                if (currentArrowIndex === 2) {
                    score += 100;
                } else {
                    score -= 50;
                }
                break;
            case 'ArrowRight':
                if (currentArrowIndex === 3) {
                    score += 100;
                } else {
                    score -= 50;
                }
                break;
            default:
                break;
        }
        pointsDisplay.textContent = `Punts: ${score}`;
        arrows[currentArrowIndex].style.display = 'none';
    };

    const endGame = () => {
        gameEnded = true; // Cambiar el estado del juego a terminado
        audio.pause(); // Asegúrate de pausar el audio
        audio.currentTime = 0; // Reiniciar el tiempo de reproducción
        arrows.forEach((arrow) => {
            arrow.style.display = 'none'; // Ocultar todas las flechas
        });;
        alert(`El joc ha finalizat!`);
    };

    showRandomArrow();
    document.addEventListener('keydown', handleKeyPress);

    audio.addEventListener('ended', endGame); // Termina el juego cuando el audio termina

    // Actualización del progreso
    const updateProgress = () => {
        if (audio.duration) {
            const percentage = (audio.currentTime / audio.duration) * 100;
            progressBar.value = percentage;

            // Si la barra de progreso llega al 100%, también termina el juego
            if (percentage >= 100) {
                endGame();
            }
        }
        requestAnimationFrame(updateProgress);
    };

    audio.play(); // Reproducir la música
    updateProgress(); // Iniciar la actualización del progreso
});
