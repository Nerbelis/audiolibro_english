<?php
/**
 * PASO 1: Control de Sesión y Seguridad
 * Iniciamos la sesión para verificar que el usuario esté autenticado. 
 * Si no ha iniciado sesión, se le redirige al login por seguridad.
 */
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

/**
 * PASO 2: Recepción del ID del Módulo por URL
 * Capturamos el parámetro 'id' que viene en la URL (ej: detalle_modulo.php?id=1).
 * Si no viene ninguno por seguridad o error, devolvemos el valor por defecto (1).
 */
$id_modulo = isset($_GET['id']) ? intval($_GET['id']) : 1;

/**
 * PASO 3: Base de Datos Simulada (10 Módulos - Nivel Básico Cotidiano)
 * Cada módulo incluye 10 palabras, 10 oraciones y preguntas de evaluación tipo quiz.
 */
$modulos = [
    1 => [
        "titulo" => "Módulo 1: Saludos y Despedidas de Todos los Días",
        "descripcion" => "Domina las 10 expresiones fundamentales para saludar, presentarte y despedirte.",
        "palabras" => [
            "1. Hello [jélo] - Hola", "2. Good morning [gud mór-ning] - Buenos días",
            "3. Good afternoon [gud áf-ter-nun] - Buenas tardes", "4. Good evening [gud ív-ning] - Buenas noches",
            "5. Goodbye [gud-bái] - Adiós", "6. Bye [bái] - Chau",
            "7. Please [plís] - Por favor", "8. Thank you [zénk iu] - Gracias",
            "9. Welcome [wél-kam] - Bienvenido", "10. Sorry [só-ri] - Lo siento"
        ],
        "oraciones" => [
            "1. Hello, nice to meet you.", "2. Good morning, how are you today?",
            "3. Good afternoon, teacher.", "4. Good evening, my friend.",
            "5. Goodbye, see you tomorrow.", "6. Bye, have a great day.",
            "7. Please help me with this.", "8. Thank you very much.",
            "9. Welcome to our class.", "10. Sorry, I am late."
        ],
        "texto_palabras" => "Hello. Good morning. Good afternoon. Good evening. Goodbye. Bye. Please. Thank you. Welcome. Sorry.",
        "texto_oraciones" => "Hello, nice to meet you. Good morning, how are you today? Good afternoon, teacher. Good evening, my friend. Goodbye, see you tomorrow. Bye, have a great day. Please help me with this. Thank you very much. Welcome to our class. Sorry, I am late.",
        "pregunta" => "¿Cómo se dice 'Buenos días' en inglés según el vocabulario de este módulo?",
        "opciones" => ["A) Good evening", "B) Good morning", "C) Good afternoon"],
        "respuesta_correcta" => 1 // Índice de la opción correcta (B = 1)
    ],
    2 => [
        "titulo" => "Módulo 2: Cortesía Básica y Expresiones Útiles",
        "descripcion" => "Aprende las 10 frases de educación elemental para desenvolverte con amabilidad.",
        "palabras" => [
            "1. Excuse me [ek-skiús mi] - Disculpe", "2. You're welcome [yor wél-kam] - De nada",
            "3. Good night [gud náit] - Buenas noches", "4. How are you? [jáu ar iu] - ¿Cómo estás?",
            "5. I am fine [ái am fáin] - Estoy bien", "6. And you? [and iu] - ¿Y tú?",
            "7. See you later [sí iu léi-ter] - Hasta luego", "8. Take care [téik kér] - Cuídate",
            "9. Yes [yés] - Sí", "10. No [nóu] - No"
        ],
        "oraciones" => [
            "1. Excuse me, where is the door?", "2. You're welcome, anytime.",
            "3. Good night, sleep well.", "4. How are you this morning?",
            "5. I am fine, thank you.", "6. And you, how do you feel?",
            "7. See you later in school.", "8. Take care of yourself.",
            "9. Yes, I understand.", "10. No, thank you."
        ],
        "texto_palabras" => "Excuse me. You are welcome. Good night. How are you? I am fine. And you? See you later. Take care. Yes. No.",
        "texto_oraciones" => "Excuse me, where is the door? You're welcome, anytime. Good night, sleep well. How are you this morning? I am fine, thank you. And you, how do you feel? See you later in school. Take care of yourself. Yes, I understand. No, thank you.",
        "pregunta" => "¿Qué significa la expresión 'You're welcome'?",
        "opciones" => ["A) De nada", "B) Disculpe", "C) Buenas noches"],
        "respuesta_correcta" => 0
    ],
    3 => [
        "titulo" => "Módulo 3: En el Colegio y Salón de Clases",
        "descripcion" => "Conoce el vocabulario esencial para nombrar útiles escolares y entender órdenes.",
        "palabras" => [
            "1. Teacher [tí-cher] - Profesor", "2. Student [stiú-dent] - Estudiante",
            "3. Notebook [nóut-buk] - Cuaderno", "4. Pen [pén] - Bolígrafo",
            "5. Pencil [pén-sil] - Lápiz", "6. Book [buk] - Libro",
            "7. Desk [désk] - Escritorio", "8. Door [dör] - Puerta",
            "9. Window [wín-dou] - Ventana", "10. Board [börd] - Pizarra"
        ],
        "oraciones" => [
            "1. The teacher is smart.", "2. I am a happy student.",
            "3. Open your notebook, please.", "4. My pen is blue.",
            "5. Write with your pencil.", "6. Read page ten in your book.",
            "7. Sit down at your desk.", "8. Close the classroom door.",
            "9. Open the window for fresh air.", "10. Look at the board."
        ],
        "texto_palabras" => "Teacher, Student, Notebook, Pen, Pencil, Book, Desk, Door, Window, Board.",
        "texto_oraciones" => "The teacher is smart. I am a happy student. Open your notebook, please. My pen is blue. Write with your pencil. Read page ten in your book. Sit down at your desk. Close the classroom door. Open the window for fresh air. Look at the board.",
        "pregunta" => "Si te dicen 'Open your notebook', ¿a qué se refieren?",
        "opciones" => ["A) Cierra la puerta", "B) Abre tu cuaderno", "C) Escribe con el lápiz"],
        "respuesta_correcta" => 1
    ],
    4 => [
        "titulo" => "Módulo 4: Números Básicos y los Colores",
        "descripcion" => "Aprende a contar elementos y a describir las tonalidades visuales de tu entorno.",
        "palabras" => [
            "1. One [wán] - Uno", "2. Two [tú] - Dos", "3. Three [zrí] - Tres",
            "4. Four [fōr] - Cuatro", "5. Five [fáiv] - Cinco", "6. Red [rét] - Rojo",
            "7. Blue [blú] - Azul", "8. Green [grín] - Verde", "9. Yellow [yé-lou] - Amarillo", "10. Black [blák] - Negro"
        ],
        "oraciones" => [
            "1. I have one apple.", "2. Give me two books.", "3. We have three classes.",
            "4. There are four chairs.", "5. Show me five fingers.", "6. The flower is red.",
            "7. The sky is blue.", "8. The tree is green.", "9. The sun is yellow.", "10. My shoes are black."
        ],
        "texto_palabras" => "One, Two, Three, Four, Five, Red, Blue, Green, Yellow, Black.",
        "texto_oraciones" => "I have one apple. Give me two books. We have three classes. There are four chairs. Show me five fingers. The flower is red. The sky is blue. The tree is green. The sun is yellow. My shoes are black.",
        "pregunta" => "¿Cómo se dice el color 'Azul' en inglés?",
        "opciones" => ["A) Red", "B) Green", "C) Blue"],
        "respuesta_correcta" => 2
    ],
    5 => [
        "titulo" => "Módulo 5: Los Miembros de la Familia",
        "descripcion" => "Identifica y describe con facilidad a los integrantes de tu núcleo familiar.",
        "palabras" => [
            "1. Mother [má-zer] - Madre", "2. Father [fá-zer] - Padre", "3. Sister [sís-ter] - Hermana",
            "4. Brother [brá-zer] - Hermano", "5. Baby [béi-bi] - Bebé", "6. Grandmother [gránd-ma-zer] - Abuela",
            "7. Grandfather [gránd-fa-zer] - Abuelo", "8. Aunt [ant] - Tía", "9. Uncle [án-kl] - Tío", "10. Family [fá-mi-li] - Familia"
        ],
        "oraciones" => [
            "1. My mother is kind.", "2. My father works hard.", "3. My sister is young.",
            "4. My brother plays soccer.", "5. The baby is sleeping.", "6. My grandmother tells stories.",
            "7. My grandfather is wise.", "8. My aunt lives near here.", "9. My uncle has a car.", "10. I love my family."
        ],
        "texto_palabras" => "Mother, Father, Sister, Brother, Baby, Grandmother, Grandfather, Aunt, Uncle, Family.",
        "texto_oraciones" => "My mother is kind. My father works hard. My sister is young. My brother plays soccer. The baby is sleeping. My grandmother tells stories. My grandfather is wise. My aunt lives near here. My uncle has a car. I love my family.",
        "pregunta" => "¿Qué significa 'Brother' en español?",
        "opciones" => ["A) Hermano", "B) Tío", "C) Padre"],
        "respuesta_correcta" => 0
    ],
    6 => [
        "titulo" => "Módulo 6: Partes del Cuerpo Humano",
        "descripcion" => "Reconoce de manera visual y verbal las partes principales del cuerpo.",
        "palabras" => [
            "1. Head [jéd] - Cabeza", "2. Eyes [áis] - Ojos", "3. Nose [nóuz] - Nariz",
            "4. Mouth [máuz] - Boca", "5. Ears [íars] - Orejas", "6. Hands [jands] - Manos",
            "7. Arms [ärms] - Brazos", "8. Legs [légz] - Piernas", "9. Feet [fít] - Pies", "10. Body [bó-di] - Cuerpo"
        ],
        "oraciones" => [
            "1. Move your head slowly.", "2. I see with my eyes.", "3. Touch your nose.",
            "4. Open your mouth.", "5. Listen with your ears.", "6. Wash your hands well.",
            "7. Stretch your arms out.", "8. My legs are tired.", "9. Walk with your feet.", "10. Keep your body healthy."
        ],
        "texto_palabras" => "Head, Eyes, Nose, Mouth, Ears, Hands, Arms, Legs, Feet, Body.",
        "texto_oraciones" => "Move your head slowly. I see with my eyes. Touch your nose. Open your mouth. Listen with your ears. Wash your hands well. Stretch your arms out. My legs are tired. Walk with your feet. Keep your body healthy.",
        "pregunta" => "¿Cómo se dice 'Ojos' en inglés?",
        "opciones" => ["A) Ears", "B) Eyes", "C) Hands"],
        "respuesta_correcta" => 1
    ],
    7 => [
        "titulo" => "Módulo 7: Los Días de la Semana y Meses",
        "descripcion" => "Organiza tus rutinas semanales y temporales manejando los días clave del calendario.",
        "palabras" => [
            "1. Monday [mán-dei] - Lunes", "2. Tuesday [túz-dei] - Martes", "3. Wednesday [wénz-dei] - Miércoles",
            "4. Thursday [zérz-dei] - Jueves", "5. Friday [frái-dei] - Viernes", "6. Saturday [sá-tur-dei] - Sábado",
            "7. Sunday [sán-dei] - Domingo", "8. Week [wík] - Semana", "9. Today [tu-déi] - Hoy", "10. Tomorrow [tu-mó-row] - Mañana"
        ],
        "oraciones" => [
            "1. Monday is the first workday.", "2. Tuesday is a busy day.", "3. Wednesday is in the middle.",
            "4. Thursday is almost Friday.", "5. Friday is very exciting.", "6. Saturday is for rest.",
            "7. Sunday is family time.", "8. This week goes fast.", "9. Today is a great day.", "10. Tomorrow we will study more."
        ],
        "texto_palabras" => "Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday, Week, Today, Tomorrow.",
        "texto_oraciones" => "Monday is the first workday. Tuesday is a busy day. Wednesday is in the middle. Thursday is almost Friday. Friday is very exciting. Saturday is for rest. Sunday is family time. This week goes fast. Today is a great day. Tomorrow we will study more.",
        "pregunta" => "¿Qué significa 'Monday'?",
        "opciones" => ["A) Lunes", "B) Viernes", "C) Domingo"],
        "respuesta_correcta" => 0
    ],
    8 => [
        "titulo" => "Módulo 8: Emociones y Estados de Ánimo",
        "descripcion" => "Aprende a comunicar cómo te sientes anímica o físicamente en cualquier momento.",
        "palabras" => [
            "1. Happy [já-pi] - Feliz", "2. Sad [sád] - Triste", "3. Tired [tái-erd] - Cansado",
            "4. Hungry [ján-gri] - Hambriento", "5. Thirsty [zérs-ti] - Sediento", "6. Sick [sík] - Enfermo",
            "7. Angry [án-gri] - Enojado", "8. Excited [ek-sáit-ed] - Emocionado", "9. Calm [käm] - Tranquilo", "10. Fine [fáin] - Bien"
        ],
        "oraciones" => [
            "1. I am very happy today.", "2. Do not be sad, smile.", "3. After work, I am tired.",
            "4. I am hungry for lunch.", "5. I am thirsty, give me water.", "6. He feels sick today.",
            "7. Do not be angry with me.", "8. She is excited for the trip.", "9. Stay calm and listen.", "10. I feel fine right now."
        ],
        "texto_palabras" => "Happy, Sad, Tired, Hungry, Thirsty, Sick, Angry, Excited, Calm, Fine.",
        "texto_oraciones" => "I am very happy today. Do not be sad, smile. After work, I am tired. I am hungry for lunch. I am thirsty, give me water. He feels sick today. Do not be angry with me. She is excited for the trip. Stay calm and listen. I feel fine right now.",
        "pregunta" => "Si alguien dice 'I am tired', ¿cómo se siente?",
        "opciones" => ["A) Feliz", "B) Cansado", "C) Enojado"],
        "respuesta_correcta" => 1
    ],
    9 => [
        "titulo" => "Módulo 9: Animales Domésticos y de la Granja",
        "descripcion" => "Identifica el vocabulario clásico de animales comunes para asociar conceptos visuales.",
        "palabras" => [
            "1. Cat [kát] - Gato", "2. Dog [dóg] - Perro", "3. Cow [káu] - Vaca",
            "4. Horse [jörs] - Caballo", "5. Pig [píg] - Cerdo", "6. Chicken [chí-ken] - Pollo",
            "7. Bird [börd] - Pájaro", "8. Duck [dák] - Pato", "9. Sheep [shíp] - Oveja", "10. Rabbit [rá-bit] - Conejo"
        ],
        "oraciones" => [
            "1. The cat sleeps on the sofa.", "2. The dog runs in the yard.", "3. The cow gives us milk.",
            "4. The horse runs very fast.", "5. The pig plays in the mud.", "6. The chicken eats corn.",
            "7. A little bird sings.", "8. The duck swims in the pond.", "9. The sheep has soft wool.", "10. The rabbit jumps high."
        ],
        "texto_palabras" => "Cat, Dog, Cow, Horse, Pig, Chicken, Bird, Duck, Sheep, Rabbit.",
        "texto_oraciones" => "The cat sleeps on the sofa. The dog runs in the yard. The cow gives us milk. The horse runs very fast. The pig plays in the mud. The chicken eats corn. A little bird sings. The duck swims in the pond. The sheep has soft wool. The rabbit jumps high.",
        "pregunta" => "¿Cómo se dice 'Perro' en inglés?",
        "opciones" => ["A) Cat", "B) Dog", "C) Cow"],
        "respuesta_correcta" => 1
    ],
    10 => [
        "titulo" => "Módulo 10: Canción Corta y Práctica Rítmica",
        "descripcion" => "Suelta la lengua cantando una tonada rítmica diseñada para fijar la pronunciación.",
        "palabras" => [
            "1. Sing [sing] - Cantar", "2. Song [sóng] - Canción", "3. Music [myú-zik] - Música",
            "4. Dance [dans] - Bailar", "5. Play [pléi] - Jugar", "6. Listen [lí-sn] - Escuchar",
            "7. Speak [spík] - Hablar", "8. Learn [lérn] - Aprender", "9. Fun [fán] - Diversión", "10. Joy [yói] - Alegría"
        ],
        "oraciones" => [
            "1. Let us sing together.", "2. This is a nice song.", "3. I love listening to music.",
            "4. Dance with energy.", "5. Play the game now.", "6. Listen to the audio carefully.",
            "7. Speak English every day.", "8. Learn new words easily.", "9. Learning English is fun.", "10. Fill your heart with joy."
        ],
        "texto_palabras" => "Sing, Song, Music, Dance, Play, Listen, Speak, Learn, Fun, Joy.",
        "texto_oraciones" => "Let us sing together. This is a nice song. I love listening to music. Dance with energy. Play the game now. Listen to the audio carefully. Speak English every day. Learn new words easily. Learning English is fun. Fill your heart with joy.",
        "pregunta" => "¿Qué significa la palabra 'Learn'?",
        "opciones" => ["A) Aprender", "B) Bailar", "C) Cantar"],
        "respuesta_correcta" => 0
    ]
];

/**
 * PASO 4: Selección del Módulo Activo
 * Comprobamos si el ID solicitado existe. Si no, cargamos por defecto el módulo 1.
 */
$actual = isset($modulos[$id_modulo]) ? $modulos[$id_modulo] : $modulos[1];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $actual['titulo']; ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8; margin: 0; padding: 30px; color: #2d3748; }
        .container { max-width: 850px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .btn-volver { display: inline-block; background-color: #4a5568; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-size: 13px; margin-bottom: 20px; font-weight: bold; }
        .btn-volver:hover { background-color: #2d3748; }
        h1 { color: #1a365d; font-size: 24px; margin-top: 0; }
        .box { background: #f8fafc; border-left: 5px solid #319795; padding: 20px; margin: 20px 0; border-radius: 0 8px 8px 0; }
        .box h3 { color: #319795; margin-top: 0; }
        .grid-list { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 14px; margin-top: 10px; }
        .list-item { background: #edf2f7; padding: 8px 12px; border-radius: 4px; }
        .btn-voz { background-color: #319795; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 15px; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; text-align: center; }
        .btn-voz:hover { background-color: #2c7a7b; }
        .btn-voz-oracion { background-color: #2b6cb0; }
        .btn-voz-oracion:hover { background-color: #2c5282; }
        .quiz-box { background: #fffaf0; border-left: 5px solid #dd6b20; padding: 20px; margin-top: 25px; border-radius: 0 8px 8px 0; }
        .quiz-box h3 { color: #dd6b20; margin-top: 0; }
        .option-label { display: block; background: #fff; padding: 10px; margin: 8px 0; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .option-label:hover { background: #edf2f7; }
        .btn-enviar { background-color: #dd6b20; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn-enviar:hover { background-color: #c05621; }
        #resultado-quiz { margin-top: 12px; font-weight: bold; font-size: 15px; }
    </style>

    <!-- PASO 5: Script de JavaScript para la Pronunciación y Evaluación del Quiz -->
    <script>
        function hablarTexto(textoIngles) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel(); // Detiene cualquier audio anterior
                let mensaje = new SpeechSynthesisUtterance(textoIngles);
                mensaje.lang = 'en-US';
                mensaje.rate = 0.85; // Velocidad pausada para principiantes
                window.speechSynthesis.speak(mensaje);
            } else {
                alert("Tu navegador no soporta voz automática.");
            }
        }

        function verificarRespuesta(correcta) {
            let seleccionada = document.querySelector('input[name="respuesta"]:checked');
            let resultado = document.getElementById("resultado-quiz");

            if (!seleccionada) {
                resultado.style.color = "#c53030";
                resultado.innerHTML = "⚠️ Por favor, selecciona una opción antes de comprobar.";
                return;
            }

            let valorUsuario = parseInt(seleccionada.value);
            if (valorUsuario === correcta) {
                resultado.style.color = "#2f855a";
                resultado.innerHTML = "🎉 ¡Excelente! Respondiste correctamente la actividad.";
            } else {
                resultado.style.color = "#c53030";
                resultado.innerHTML = "❌ Respuesta incorrecta. Vuelve a revisar las palabras del módulo e inténtalo de nuevo.";
            }
        }
    </script>
</head>
<body>

<div class="container">
    <!-- Enlace de retorno al panel principal -->
    <a href="dashboard.php" class="btn-volver">⬅️ Volver al Panel Principal</a>
    
    <!-- Título y descripción dinámica -->
    <h1><?php echo $actual['titulo']; ?></h1>
    <p style="font-size: 16px; color: #4a5568;"><?php echo $actual['descripcion']; ?></p>

    <!-- Sección de las 10 Palabras Clave y Botón de Voz -->
    <div class="box">
        <h3>1. Las 10 Palabras Clave y su Fonética</h3>
        <div class="grid-list">
            <?php foreach($actual['palabras'] as $palabra): ?>
                <div class="list-item"><?php echo $palabra; ?></div>
            <?php endforeach; ?>
        </div>
        <button class="btn-voz" onclick="hablarTexto('<?php echo addslashes($actual['texto_palabras']); ?>')">
            🔊 Escuchar la Pronunciación de las 10 Palabras
        </button>
    </div>

    <!-- Sección de las 10 Oraciones y Botón de Voz -->
    <div class="box" style="border-left-color: #2b6cb0;">
        <h3 style="color: #2b6cb0;">2. Las 10 Oraciones Cotidianas de Práctica</h3>
        <div style="font-size: 14px; line-height: 1.6;">
            <?php foreach($actual['oraciones'] as $oracion): ?>
                <div><?php echo $oracion; ?></div>
            <?php endforeach; ?>
        </div>
        <button class="btn-voz btn-voz-oracion" onclick="hablarTexto('<?php echo addslashes($actual['texto_oraciones']); ?>')">
            🔊 Escuchar la Pronunciación de las 10 Oraciones
        </button>
    </div>

    <!-- Sección de Actividad / Tarea Entregable del Módulo -->
    <div class="quiz-box">
        <h3>3. Actividad de Evaluación del Módulo</h3>
        <p style="font-size: 14px; font-weight: bold;"><?php echo $actual['pregunta']; ?></p>
        
        <form id="form-quiz" onsubmit="return false;">
            <?php foreach($actual['opciones'] as $index => $opcion): ?>
                <label class="option-label">
                    <input type="radio" name="respuesta" value="<?php echo $index; ?>"> <?php echo $opcion; ?>
                </label>
            <?php endforeach; ?>
            
            <button type="button" class="btn-enviar" onclick="verificarRespuesta(<?php echo $actual['respuesta_correcta']; ?>)">Comprobar Respuesta</button>
        </form>
        
        <div id="resultado-quiz"></div>
    </div>
</div>

</body>
</html>