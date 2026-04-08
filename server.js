const express = require('express');
const session = require('express-session');
const mysql = require('mysql2/promise');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = 3000;

// Middleware
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));
app.use(session({
  secret: 'galaxyv-support-secret',
  resave: false,
  saveUninitialized: false
}));

// Load branding config
const config = JSON.parse(fs.readFileSync(path.join(__dirname, 'config.json'), 'utf8'));
const branding = config.branding;
const dbConfig = config.database;

// Make branding available in all views
app.use((req, res, next) => {
  res.locals.branding = branding;
  next();
});

let pool;

// ---- Database Initialization ----
async function initDatabase() {
  // Connect without database to create it if needed
  const tempConn = await mysql.createConnection({
    host: dbConfig.host,
    user: dbConfig.user,
    password: dbConfig.password
  });
  await tempConn.query(`CREATE DATABASE IF NOT EXISTS \`${dbConfig.database}\``);
  await tempConn.end();

  // Create pool using the support database
  pool = mysql.createPool({
    host: dbConfig.host,
    user: dbConfig.user,
    password: dbConfig.password,
    database: dbConfig.database,
    waitForConnections: true,
    connectionLimit: dbConfig.connectionLimit || 10
  });

  // Create tables
  await pool.query(`
    CREATE TABLE IF NOT EXISTS users (
      user_id VARCHAR(255) NOT NULL PRIMARY KEY,
      user_name VARCHAR(255) NOT NULL,
      password VARCHAR(255) NOT NULL,
      email VARCHAR(255),
      access INT DEFAULT 0,
      admin INT DEFAULT 0
    )
  `);

  // Add admin column if it doesn't exist (for existing tables)
  try {
    await pool.query(`ALTER TABLE users ADD COLUMN admin INT DEFAULT 0`);
  } catch (e) {
    // Column already exists, ignore
  }

  await pool.query(`
    CREATE TABLE IF NOT EXISTS data (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user VARCHAR(255),
      grund VARCHAR(255),
      ausgang VARCHAR(255),
      username VARCHAR(255),
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
  `);

  // Add created_at column if it doesn't exist (for existing tables)
  try {
    await pool.query(`ALTER TABLE data ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP`);
  } catch (e) {
    // Column already exists, ignore
  }

  console.log('Database and tables initialized.');
}

// ---- Helper Functions ----
function randomNum(length) {
  if (length < 5) length = 5;
  const len = Math.floor(Math.random() * (length - 4 + 1)) + 4;
  let text = '';
  for (let i = 0; i < len; i++) {
    text += Math.floor(Math.random() * 10);
  }
  return text;
}

async function checkLogin(req, res) {
  if (req.session && req.session.user_id) {
    const [rows] = await pool.query('SELECT * FROM users WHERE user_id = ? LIMIT 1', [req.session.user_id]);
    if (rows.length > 0) {
      return rows[0];
    }
  }
  res.redirect('/login');
  return null;
}

// ---- Routes ----

// GET /login
app.get('/login', (req, res) => {
  res.render('login', { error: null });
});

// POST /login
app.post('/login', async (req, res) => {
  const { user_name, password } = req.body;

  if (user_name && password && isNaN(user_name)) {
    const [rows] = await pool.query('SELECT * FROM users WHERE user_name = ? LIMIT 1', [user_name]);
    if (rows.length > 0) {
      const user = rows[0];
      if (user.password === password && user.access == 1) {
        req.session.user_id = user.user_id;
        return res.redirect('/');
      }
    }
    return res.render('login', { error: 'Falscher Benutzername oder Passwort!' });
  } else {
    return res.render('login', { error: 'Falscher Benutzername oder Passwort!' });
  }
});

// GET /signup
app.get('/signup', (req, res) => {
  res.render('signup', { error: null });
});

// POST /signup
app.post('/signup', async (req, res) => {
  const { user_name, password, email } = req.body;

  if (user_name && password && isNaN(user_name)) {
    const user_id = randomNum(20);
    await pool.query('INSERT INTO users (user_id, user_name, password, email) VALUES (?, ?, ?, ?)', [user_id, user_name, password, email]);
    return res.redirect('/login');
  } else {
    return res.render('signup', { error: 'Please enter some valid information!' });
  }
});

// GET / (index)
app.get('/', async (req, res) => {
  const user_data = await checkLogin(req, res);
  if (!user_data) return;

  // Personal tickets
  const [myTickets] = await pool.query('SELECT * FROM `data` WHERE username = ? ORDER BY created_at DESC', [user_data.user_name]);
  // Total ticket count
  const [totalResult] = await pool.query('SELECT COUNT(*) as total FROM `data`');
  const totalTickets = totalResult[0].total;

  // Tickets per day
  const [ticketsPerDay] = await pool.query(
    'SELECT DATE(created_at) as day, COUNT(*) as count FROM `data` WHERE username = ? GROUP BY DATE(created_at) ORDER BY day DESC',
    [user_data.user_name]
  );

  // Tickets per month
  const [ticketsPerMonth] = await pool.query(
    "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM `data` WHERE username = ? GROUP BY DATE_FORMAT(created_at, '%Y-%m') ORDER BY month DESC",
    [user_data.user_name]
  );

  res.render('index', { user_data, myTickets, totalTickets, ticketsPerDay, ticketsPerMonth });
});

// GET /form
app.get('/form', async (req, res) => {
  const user_data = await checkLogin(req, res);
  if (!user_data) return;
  res.render('form', { user_data });
});

// POST /insert
app.post('/insert', async (req, res) => {
  const { user, grund, ausgang, name } = req.body;
  await pool.query('INSERT INTO `data` (`user`, `grund`, `ausgang`, `username`) VALUES (?, ?, ?, ?)', [user, grund, ausgang, name]);
  res.redirect('/form');
});

// GET /admin
app.get('/admin', async (req, res) => {
  const user_data = await checkLogin(req, res);
  if (!user_data) return;
  if (user_data.admin != 1) return res.redirect('/');

  const [users] = await pool.query('SELECT user_id, user_name, email, access, admin FROM users');
  const [tickets] = await pool.query('SELECT * FROM `data` ORDER BY created_at DESC');
  const [ticketCounts] = await pool.query('SELECT username, COUNT(*) as count FROM `data` GROUP BY username');

  res.render('admin', { user_data, users, tickets, ticketCounts });
});

// GET /admin/user/:user_id - User detail page
app.get('/admin/user/:user_id', async (req, res) => {
  const user_data = await checkLogin(req, res);
  if (!user_data) return;
  if (user_data.admin != 1) return res.redirect('/');

  const { user_id } = req.params;
  const [userRows] = await pool.query('SELECT user_id, user_name, email, access, admin FROM users WHERE user_id = ? LIMIT 1', [user_id]);
  if (userRows.length === 0) return res.redirect('/admin');
  const targetUser = userRows[0];

  // All tickets by this user
  const [userTickets] = await pool.query('SELECT * FROM `data` WHERE username = ? ORDER BY created_at DESC', [targetUser.user_name]);

  // Tickets per day
  const [ticketsPerDay] = await pool.query(
    `SELECT DATE(created_at) as day, COUNT(*) as count FROM \`data\` WHERE username = ? GROUP BY DATE(created_at) ORDER BY day DESC`,
    [targetUser.user_name]
  );

  // Tickets per month
  const [ticketsPerMonth] = await pool.query(
    `SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM \`data\` WHERE username = ? GROUP BY DATE_FORMAT(created_at, '%Y-%m') ORDER BY month DESC`,
    [targetUser.user_name]
  );

  // Total ticket count
  const totalTickets = userTickets.length;

  res.render('user-detail', { user_data, targetUser, userTickets, ticketsPerDay, ticketsPerMonth, totalTickets });
});

// POST /admin/activate
app.post('/admin/activate', async (req, res) => {
  const user_data = await checkLogin(req, res);
  if (!user_data || user_data.admin != 1) return res.redirect('/');
  const { user_id } = req.body;
  await pool.query('UPDATE users SET access = 1 WHERE user_id = ?', [user_id]);
  res.redirect('/admin');
});

// POST /admin/deactivate
app.post('/admin/deactivate', async (req, res) => {
  const user_data = await checkLogin(req, res);
  if (!user_data || user_data.admin != 1) return res.redirect('/');
  const { user_id } = req.body;
  await pool.query('UPDATE users SET access = 0 WHERE user_id = ?', [user_id]);
  res.redirect('/admin');
});

// POST /admin/delete
app.post('/admin/delete', async (req, res) => {
  const user_data = await checkLogin(req, res);
  if (!user_data || user_data.admin != 1) return res.redirect('/');
  const { user_id } = req.body;
  await pool.query('DELETE FROM users WHERE user_id = ?', [user_id]);
  res.redirect('/admin');
});

// GET /logout
app.get('/logout', (req, res) => {
  if (req.session && req.session.user_id) {
    delete req.session.user_id;
  }
  res.redirect('/login');
});

// Redirect .php URLs for compatibility
app.get('/login.php', (req, res) => res.redirect('/login'));
app.get('/signup.php', (req, res) => res.redirect('/signup'));
app.get('/index.php', (req, res) => res.redirect('/'));
app.get('/form.php', (req, res) => res.redirect('/form'));
app.get('/logout.php', (req, res) => res.redirect('/logout'));

// ---- Start Server ----
initDatabase().then(() => {
  app.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
  });
}).catch(err => {
  console.error('Failed to initialize database:', err);
  process.exit(1);
});
