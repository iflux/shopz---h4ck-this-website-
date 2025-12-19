CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    address TEXT,
    remember_token VARCHAR(255),
    reset_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50),
    stock INT DEFAULT 100,
    image VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'pending',
    shipping_address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    user_id INT,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    discount INT NOT NULL,
    used INT DEFAULT 0,
    max_uses INT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS flags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flag_name VARCHAR(100),
    flag_value VARCHAR(100)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@shopz.local', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
('john_doe', 'john@example.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'user'),
('jane_smith', 'jane@example.com', 'e99a18c428cb38d5f260853678922e03', 'user'),
('test', 'test@test.com', '098f6bcd4621d373cade4e832627b4f6', 'user'),
('FLAG{sqli_union_extracted}', 'flag@secret.com', 'flag', 'flag');

INSERT INTO products (name, description, price, category, stock, image) VALUES
('Gaming Laptop X1', 'High performance gaming laptop with RTX 4080', 1499.99, 'laptops', 50, 'laptop1.jpg'),
('Mechanical Keyboard RGB', 'Cherry MX Blue switches with RGB backlight', 129.99, 'peripherals', 200, 'keyboard1.jpg'),
('27" 4K Monitor', 'IPS panel, 144Hz, HDR support', 549.99, 'monitors', 75, 'monitor1.jpg'),
('Wireless Mouse Pro', 'Ergonomic design, 16000 DPI sensor', 79.99, 'peripherals', 300, 'mouse1.jpg'),
('USB-C Hub 7-in-1', 'HDMI, USB 3.0, SD card reader', 49.99, 'accessories', 500, 'hub1.jpg'),
('Webcam 4K HDR', 'Auto-focus, noise cancelling mic', 149.99, 'accessories', 150, 'webcam1.jpg'),
('Gaming Headset 7.1', 'Surround sound, detachable mic', 89.99, 'audio', 250, 'headset1.jpg'),
('SSD 1TB NVMe', 'Read 7000MB/s, Write 5000MB/s', 109.99, 'storage', 400, 'ssd1.jpg'),
('RAM 32GB DDR5', '6000MHz, RGB heatsink', 159.99, 'components', 180, 'ram1.jpg'),
('Graphics Card RTX 4070', '12GB GDDR6X, Ray Tracing', 599.99, 'components', 30, 'gpu1.jpg');

INSERT INTO orders (user_id, total, status, shipping_address, notes) VALUES
(2, 1629.98, 'completed', '123 Main St, New York, NY 10001', 'FLAG{idor_orders_exposed}'),
(3, 259.98, 'shipped', '456 Oak Ave, Los Angeles, CA 90001', 'Gift wrap please'),
(2, 549.99, 'pending', '123 Main St, New York, NY 10001', 'Urgent delivery');

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 1499.99),
(1, 2, 1, 129.99),
(2, 2, 2, 129.99),
(3, 3, 1, 549.99);

INSERT INTO coupons (code, discount, max_uses) VALUES
('WELCOME10', 10, 100),
('SUMMER25', 25, 50),
('VIP50', 50, 10),
('FLAG{logic_coupon_reuse}', 99, 999);

INSERT INTO flags (flag_name, flag_value) VALUES
('sql_hidden', 'FLAG{sqli_blind_boolean}'),
('crypto_secret', 'FLAG{crypto_md5_cracked}'),
('db_access', 'FLAG{mysql_root_pwned}');
