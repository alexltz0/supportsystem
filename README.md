# Support-Protokoll System

Ein modernes, vollständiges Ticket-Management-System mit Glassmorphism-UI, Admin-Panel und Echtzeit-Statistiken.

🌐 **Portfolio & Demo:** [https://alexltz0.github.io/supportsystem](https://alexltz0.github.io/supportsystem)

---

## Features

- **Authentifizierung** — Login & Registrierung mit Session-Management. Neue Benutzer müssen von Admins freigeschaltet werden.
- **Ticket-System** — Support-Tickets eintragen mit Discord-User, Grund und Ausgang. Automatische Zeitstempel.
- **Persönliches Dashboard** — Eigene Ticket-Übersicht, Aktivitäts-Balkendiagramm, Monatsstatistik und Durchschnittswerte.
- **Admin Panel** — Benutzerverwaltung (Freischalten, Sperren, Löschen), Ticket-Übersicht, klickbare User-Profile mit detaillierten Statistiken.
- **Anpassbar** — Farben über `colors.css`, Branding und Datenbank über `config.json`.
- **Glassmorphism UI** — Modernes Design mit Glas-Effekten, Animationen, Gradient-Accents und responsive Layout.

## Tech Stack

| Technologie | Einsatz |
|---|---|
| **Node.js** | Runtime |
| **Express** | Web Framework |
| **MySQL** | Datenbank |
| **EJS** | Templating |
| **CSS** | Glassmorphism UI |

## Projektstruktur

```
supportsystem/
├── server.js              ← Express Server + Routen
├── config.json            ← Branding + Datenbank-Config
├── package.json
├── views/
│   ├── login.ejs
│   ├── signup.ejs
│   ├── index.ejs          ← Dashboard
│   ├── form.ejs           ← Ticket erstellen
│   ├── admin.ejs          ← Admin Panel
│   └── user-detail.ejs    ← User Statistiken
├── public/
│   ├── css/
│   │   ├── colors.css     ← Farb-Konfiguration
│   │   └── app.css
│   ├── js/
│   └── img/
└── docs/
    └── index.html         ← Portfolio-Seite
```

## Schnellstart

### 1. Abhängigkeiten installieren

```bash
npm install
```

### 2. Konfigurieren

Passe `config.json` an deine Umgebung an:

```json
{
  "branding": {
    "name": "MeinProjekt",
    "subtitle": "Support-System",
    "logo": "img/logo.png"
  },
  "database": {
    "host": "localhost",
    "user": "root",
    "password": "1234",
    "database": "support",
    "connectionLimit": 10
  }
}
```

### 3. Farben anpassen (optional)

Ändere die Akzentfarben in `public/css/colors.css` — alles passt sich automatisch an:

```css
:root {
  --accent: #ff0079;
  --accent-hover: #cc0062;
  --accent-light: #ff6fb5;
  /* ... weitere Farben ... */
}
```

### 4. Server starten

```bash
npm start
```

> MySQL muss laufen. Datenbank und Tabellen werden automatisch erstellt.

Der Server läuft unter **http://localhost:3000**.

## Erster Admin-Benutzer

Nach der Registrierung ist ein neuer Account zunächst gesperrt (`access = 0`). Um den ersten Admin zu erstellen, setze die Werte direkt in der Datenbank:

```sql
UPDATE users SET access = 1, admin = 1 WHERE user_name = 'deinname';
```

Danach können weitere Benutzer über das Admin Panel freigeschaltet werden.

## Lizenz

MIT
