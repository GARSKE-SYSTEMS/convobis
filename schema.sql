-- Database schema for Conversation-CRM

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    name TEXT
);

-- Clients table
CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    createdAt TEXT NOT NULL
);

-- Topics table
CREATE TABLE IF NOT EXISTS topics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    clientId INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT,
    createdAt TEXT NOT NULL,
    isArchived INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (clientId) REFERENCES clients(id)
);

-- Messages table
CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    topicId INTEGER NOT NULL,
    authorId INTEGER,
    authorType TEXT NOT NULL,
    content TEXT NOT NULL,
    createdAt TEXT NOT NULL,
    replyToId INTEGER NULL,
    isPinned INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (topicId) REFERENCES topics(id)
);

-- Attachments table
CREATE TABLE IF NOT EXISTS attachments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    messageId INTEGER NOT NULL,
    filename TEXT NOT NULL,
    filepath TEXT NOT NULL,
    createdAt TEXT NOT NULL,
    FOREIGN KEY (messageId) REFERENCES messages(id)
);

-- Contacts table
CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    clientId INTEGER NOT NULL,
    type TEXT NOT NULL,
    value TEXT NOT NULL,
    FOREIGN KEY (clientId) REFERENCES clients(id)
);

-- Addresses table
CREATE TABLE IF NOT EXISTS addresses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    clientId INTEGER NOT NULL,
    street TEXT,
    city TEXT,
    state TEXT,
    zip TEXT,
    country TEXT,
    FOREIGN KEY (clientId) REFERENCES clients(id)
);

-- Notes table
CREATE TABLE IF NOT EXISTS notes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    clientId INTEGER NOT NULL,
    content TEXT NOT NULL,
    createdAt TEXT NOT NULL,
    FOREIGN KEY (clientId) REFERENCES clients(id)
);

-- References table
CREATE TABLE IF NOT EXISTS references (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    clientId INTEGER NOT NULL,
    title TEXT NOT NULL,
    url TEXT,
    FOREIGN KEY (clientId) REFERENCES clients(id)
);

-- Tags table
CREATE TABLE IF NOT EXISTS tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    clientId INTEGER NOT NULL,
    name TEXT NOT NULL,
    FOREIGN KEY (clientId) REFERENCES clients(id)
);

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    userId INTEGER NOT NULL,
    type TEXT NOT NULL,
    referenceId INTEGER,
    messageId INTEGER,
    isRead INTEGER NOT NULL DEFAULT 0,
    createdAt TEXT NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id)
);

-- Reminders table
CREATE TABLE IF NOT EXISTS reminders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    userId INTEGER NOT NULL,
    messageId INTEGER NOT NULL,
    remindAt TEXT NOT NULL,
    isSent INTEGER NOT NULL DEFAULT 0,
    sentAt TEXT,
    FOREIGN KEY (userId) REFERENCES users(id),
    FOREIGN KEY (messageId) REFERENCES messages(id)
);
