# YourSpace - Un clone di MySpace

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

**YourSpace** è un social network minimalista sviluppato in **PHP nativo** e **MySQL**.  
Il progetto nasce come esercizio di sviluppo Full Stack per gestire l'intero ciclo di vita di un utente: dalla registrazione alla personalizzazione del proprio spazio personale.

---

## ✨ Funzionalità

* 🔐 **Auth System**: Registrazione e Login sicuri.
* 🛡️ **Sicurezza**: Password hashate (Bcrypt) e protezione da SQL Injection.
* 👤 **Profilo Personale**: Ogni utente ha il suo "Space" con bio modificabile.
* 🎨 **Temi Dinamici**: Personalizzazione del colore di accento (salvato nel DB).
* 🍪 **Session Management**: Gestione avanzata delle sessioni PHP.
* 🚫 **Account Management**: Possibilità di eliminare il proprio profilo.

---

## 🛠️ Stack Tecnologico

* **Backend**: PHP 8.x (Procedurale)
* **Database**: MySQL / MariaDB
* **Frontend**: HTML5, CSS3 Moderno (Glassmorphism UI)
* **Server**: XAMPP / MAMP / Apache

---

## ⚙️ Installazione e Setup

Segui questi passaggi per lanciare **YourSpace** in locale:

### 1. Clona il repository
Spostati nella cartella `htdocs` (o `www`) del tuo server locale:

bash
git clone [https://github.com/tuo-username/yourspace.git](https://github.com/tuo-username/yourspace.git)
cd yourspace
2. Configurazione Database
Apri il tuo gestore database (es. phpMyAdmin).

Crea un nuovo database chiamato yourspace.

Esegui lo script SQL qui sotto nella scheda SQL:

SQL

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    theme_color VARCHAR(20) DEFAULT '#8b5cf6',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
3. Connessione al DB
Apri il file config.php e aggiorna le credenziali:

PHP

<?php
$host = 'localhost';
$db   = 'yourspace';  // Nome aggiornato
$user = 'root';
$pass = '';           // Metti la password se necessaria (es. 'root' su MAMP)

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connessione fallita: " . mysqli_connect_error());
}
?>
4. Avvia
Apri il browser e naviga su: http://localhost/yourspace/

📂 Struttura File
Plaintext

/yourspace
│

├── index.php           # Home / Landing Page

├── login.php           # Form di accesso

├── register.php        # Form di registrazione

├── profile.php         # Il tuo "Space" (Dashboard)

├── config.php          # Configurazione DB

├── logout.php          # Script logout

├── style.css           # UI / UX Design

└── README.md           # Documentazione

📸 Anteprima
Inserisci qui gli screenshot del progetto.

📝 Licenza
Questo progetto è distribuito sotto licenza MIT.
