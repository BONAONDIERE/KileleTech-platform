-- ============================================================
-- Kilele Tech — Database Schema
-- Import this in phpMyAdmin (XAMPP) or via:
--   mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS kilele_tech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kilele_tech;

-- ------------------------------------------------------------
-- Admin users (people who can log into /admin)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name     VARCHAR(100) DEFAULT '',
    role          VARCHAR(20)  DEFAULT 'admin',
    created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Contact form submissions (contact.php)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    phone       VARCHAR(30)   DEFAULT '',
    email       VARCHAR(150)  NOT NULL,
    subject     VARCHAR(200)  DEFAULT '',
    message     TEXT          NOT NULL,
    status      ENUM('new','read','replied') DEFAULT 'new',
    admin_reply TEXT          NULL,
    replied_at  TIMESTAMP     NULL,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Quote requests (quote.php)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS quote_requests (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    company     VARCHAR(150)  DEFAULT '',
    phone       VARCHAR(30)   NOT NULL,
    email       VARCHAR(150)  NOT NULL,
    service     VARCHAR(100)  DEFAULT '',
    package     VARCHAR(50)   DEFAULT '',
    message     TEXT          NOT NULL,
    status      ENUM('new','read','replied') DEFAULT 'new',
    admin_reply TEXT          NULL,
    replied_at  TIMESTAMP     NULL,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Newsletter signups (footer form, every page)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    email          VARCHAR(150) NOT NULL UNIQUE,
    subscribed_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- No admin user is inserted here on purpose — run admin/setup.php
-- once in your browser after importing this file. It creates the
-- first admin account with a properly hashed password (hashing
-- needs to happen in PHP, not in a raw SQL script).