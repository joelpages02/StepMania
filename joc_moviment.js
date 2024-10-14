// joc_moviment.js

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    // Obtener todas las flechas
    const arrows = document.querySelectorAll('.fletxes .imgfletxa');

    // Elemento para mostrar los puntos
    const pointsDisplay = document.querySelector('.punts a');

    // Variables de puntuación
    let score = 0;

    // Elemento de audio
    const audio = new Audio('musica_2.mp3'); // Asegúrate de que la ruta sea correcta según tu estructura de archivos

    // Variable para guardar la flecha que se está mostrando actualmente
    let currentArrowIndex = -1;

    // Función para mostrar una flecha aleatoria y ocultar las demás
    const showRandomArrow = () => {
        // Ocultar todas las flechas
        arrows.forEach((arrow) => {
            arrow.style.display = 'none'; // Oculta todas las flechas
        });

        // Generar un índice aleatorio entre 0 y el número de flechas
        currentArrowIndex = Math.floor(Math.random() * arrows.length);

        // Mostrar solo la flecha aleatoria
        arrows[currentArrowIndex].style.display = 'block';

        // Establecer el tiempo para mostrar la próxima flecha
        const displayDuration = 1000; // Duración de cada flecha en milisegundos

        // Mostrar la próxima flecha después de un tiempo definido
        setTimeout(showRandomArrow, displayDuration);
    };

    // Función para manejar el puntaje al presionar una tecla
    const handleKeyPress = (event) => {
        // Obtener el código de la tecla presionada
        switch (event.key) {
            case 'ArrowLeft':
                // La flecha izquierda
                if (currentArrowIndex === 0) {
                    score += 100; // Sumar 100 puntos
                    pointsDisplay.textContent = `Punts: ${score}`; // Actualizar la visualización de puntos
                    arrows[currentArrowIndex].style.display = 'none'; // Ocultar la flecha correcta
                }
                break;
            case 'ArrowUp':
                // La flecha arriba
                if (currentArrowIndex === 1) {
                    score += 100; // Sumar 100 puntos
                    pointsDisplay.textContent = `Punts: ${score}`; // Actualizar la visualización de puntos
                    arrows[currentArrowIndex].style.display = 'none'; // Ocultar la flecha correcta
                }
                break;
            case 'ArrowDown':
                // La flecha abajo
                if (currentArrowIndex === 2) {
                    score += 100; // Sumar 100 puntos
                    pointsDisplay.textContent = `Punts: ${score}`; // Actualizar la visualización de puntos
                    arrows[currentArrowIndex].style.display = 'none'; // Ocultar la flecha correcta
                }
                break;
            case 'ArrowRight':
                // La flecha derecha
                if (currentArrowIndex === 3) {
                    score += 100; // Sumar 100 puntos
                    pointsDisplay.textContent = `Punts: ${score}`; // Actualizar la visualización de puntos
                    arrows[currentArrowIndex].style.display = 'none'; // Ocultar la flecha correcta
                }
                break;
            default:
                break;
        }
    };

    // Reproducir la canción
    audio.play();

    // Iniciar mostrando una flecha aleatoria
    showRandomArrow();

    // Agregar un event listener para detectar la presión de teclas
    document.addEventListener('keydown', handleKeyPress);

     // Detener la aparición de flechas al finalizar la canción
     audio.addEventListener('ended', () => {
        arrows.forEach((arrow) => {
            arrow.style.display = 'none'; // Asegúrate de ocultar las flechas al final
        });
    });
});
