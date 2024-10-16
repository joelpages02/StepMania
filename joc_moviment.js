document.addEventListener('DOMContentLoaded', () => {
    // Seleccionar totes les fletxes i el display de punts
    const arrows = document.querySelectorAll('.fletxes .imgfletxa');
    const pointsDisplay = document.querySelector('.punts a');
    const progressBar = document.getElementById('file');
    // Obtenir l'element d'àudio directament
    const audio = document.getElementById('audio');
    let score = 0;
    // Variables per a la música i el títol de la cançó
    const music = "<?php echo $music; ?>";
    const songTitle = "<?php echo $title; ?>";

    let currentArrowIndex = -1;
    let gameEnded = false;

    // Funció per mostrar una fletxa aleatòria
    const showRandomArrow = () => {
        if (gameEnded) return;

        arrows.forEach((arrow) => {
            arrow.style.display = 'none';
        });

        currentArrowIndex = Math.floor(Math.random() * arrows.length);
        arrows[currentArrowIndex].style.display = 'block';

        setTimeout(showRandomArrow, 1000);
    };

    // Funció per gestionar la pressió de les tecles
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
        // Actualitzar el text del display de punts
        pointsDisplay.textContent = `Punts: ${score}`;
        // Amagar la fletxa actual
        arrows[currentArrowIndex].style.display = 'none';
    };

    // Funció per finalitzar el joc
    const endGame = () => {
        gameEnded = true;
        audio.pause();
        audio.currentTime = 0;
        arrows.forEach((arrow) => {
            arrow.style.display = 'none'; 
        });
        // Assignar el score aquí
        puntuacionInput.value = score;
        alert(`El joc ha finalitzat!`);
    };
    
    // Mostrar la primera fletxa aleatòria
    showRandomArrow();
    // Escoltar l'esdeveniment de pressionar una tecla
    document.addEventListener('keydown', handleKeyPress);

    // Finalitzar el joc quan l'àudio acabi
    audio.addEventListener('ended', endGame);

    // Funció per actualitzar el progrés
    const updateProgress = () => {
        if (audio.duration) {
            const percentage = (audio.currentTime / audio.duration) * 100;
            progressBar.value = percentage;

            // Si la barra de progrés arriba al 100%, també finalitza el joc
            if (percentage >= 100) {
                endGame();
            }
        }
        // Sol·licitar la següent actualització
        requestAnimationFrame(updateProgress);
    };

    // Reproduir la música
    audio.play();
    // Iniciar l'actualització del progrés
    updateProgress();
});
