CREATE DATABASE IF NOT EXISTS nalya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nalya;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS favorites;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(30) NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  description TEXT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  image VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_category FOREIGN KEY(category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_products_active (is_active),
  INDEX idx_products_name (name)
) ENGINE=InnoDB;

CREATE TABLE favorites (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_favorite (user_id,product_id),
  CONSTRAINT fk_favorites_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_favorites_product FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(40) NOT NULL UNIQUE,
  user_id INT UNSIGNED NOT NULL,
  customer_name VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  address TEXT NOT NULL,
  city VARCHAR(100) NOT NULL,
  notes TEXT NULL,
  total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('En attente','Confirmée','En préparation','Expédiée','Livrée','Annulée') NOT NULL DEFAULT 'En attente',
  payment_method VARCHAR(50) NOT NULL DEFAULT 'Paiement à la livraison',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE RESTRICT,
  INDEX idx_orders_status (status),
  INDEX idx_orders_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(160) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_order_items_order FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  subject VARCHAR(190) NULL,
  message TEXT NOT NULL,
  is_handled TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO categories(name) VALUES
('Bagues'),('Colliers'),('Bracelets'),('Boucles d''oreilles');

-- Images placeholders locales. Vous pouvez les remplacer via l'administration.
INSERT INTO products(category_id,name,description,price,stock,image,is_active) VALUES
(1,'Bague Solène','Une bague délicate au fini doré, pensée pour accompagner vos tenues du quotidien.',129.00,12,'assets/images/product-placeholder.svg',1),
(2,'Collier Éclat','Un collier fin et lumineux qui apporte une touche subtile à votre style.',189.00,8,'assets/images/product-placeholder.svg',1),
(3,'Bracelet Alba','Un bracelet minimaliste à porter seul ou en accumulation.',99.00,15,'assets/images/product-placeholder.svg',1),
(4,'Boucles Lune','Des boucles légères et élégantes pour illuminer votre visage.',149.00,10,'assets/images/product-placeholder.svg',1),
(2,'Collier Perle','Une note douce et intemporelle inspirée des perles classiques.',219.00,6,'assets/images/product-placeholder.svg',1),
(1,'Bague Aura','Une pièce simple au caractère affirmé, facile à associer.',115.00,9,'assets/images/product-placeholder.svg',1);

-- Après installation, créez un compte via register.php puis promouvez-le en admin :
-- UPDATE users SET role='admin' WHERE email='votre-email@example.com';
