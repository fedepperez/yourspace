
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

*  **Backend**: PHP 8.x (Procedurale)
*  **Database**: MySQL / MariaDB
*  **Frontend**: HTML5, CSS3 Moderno (Glassmorphism UI)
*  **Server**: Apache

---

## ⚙️ Installazione e Setup

Segui questi passaggi per lanciare **YourSpace** in locale: 

### 1. Clona il repository

#### Bare metal (non Docker)

**Assicurati di aver installato Composer sulla macchina host.**

Spostati nella cartella `htdocs` (o `www`) del tuo server locale:

```bash
git  clone [https://github.com/fedepperez/yourspace.git](https://github.com/fedepperez/yourspace.git)
cd  yourspace
```

Crea un file `.env` popolando i dati seguendo l'esempio in `.env.example`

  

#### Docker Compose

Clona il repository linkato sopra in un percorso a tua scelta.
Spostatici dentro con `cd`.

Crea un file `.env` popolando i dati seguendo l'esempio in `.env.example`

Builda le immagini:
```bash
docker-compose  up  --build  -d
```

### 2. Build progetto
```bash
# aggancia una shell al container
# ignora se non stai usando docker

docker  ps  # verifica il nome del container
docker-exec  -it <nome  container> sh
```
```bash
# installa le dipendenze
composer  i
```


### 3. Configurazione Database

**(Se non stai usando Docker)** assicurati che sulla macchina host sia in esecuzione un'istanza di MySQL.

Esegui gli script presenti dentro `/database/migrations`.

**(Se stai usando Docker)** raggiungi phpMyAdmin a `localhost:8081` o aggancia una shell al container `mysql` per eseguire i comandi da CLI.


Esegui gli script presenti dentro `/database/migrations`.

### 4. Avvia

Se non stai usando Docker puoi raggiungere il progetto a: http://localhost/yourspace/

Se stai usando Docker trovi:
|servizio|url|
|-----|----|----
|phpmyadmin|[localhost:8081](localhost:8081)|
|web|[localhost:8080](localhost:8080)|


📂 Struttura File

```plaintext
/yourspace
│
├── index.php # Home / Landing Page 
├── login.php # Form di accesso
├── register.php # Form di registrazione
├── profile.php # Il tuo "Space" (Dashboard)
├── config.php # Configurazione DB
├── logout.php # Script logout
├── style.css # UI / UX Design
└── README.md # Documentazione
```

📝 Licenza:

Questo progetto è distribuito sotto licenza MIT.
