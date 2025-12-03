# Inventory Management Web Application
A simple PHP-based inventory management system that allows an admin to register, log in, add products, view products, and upload product images.
This project uses PHP, MySQL, and custom CSS, and is hosted on the Georgian College server.

## Features

### Admin System
- Admin Registration
- Secure Login with password hashing
- Sessions for authentication
- Logout option

### Product Management
- Add new products (name, description, price, quantity)
- Upload and store product images
- View all products
- Clean layout for easy viewing

### Custom Styling
- Fully styled with custom CSS (styles.css)
- Simple UI for better user experience

## Technologies Used
- PHP 8+
- MySQL
- HTML5
- CSS3
- Git & GitHub
- Apache Web Server (Georgian College hosting)

## Project Structure
```
inventory_project/
│
├── classes/
│   └── Database.php
│
├── uploads/
│   └── product images stored here
│
├── index.php
├── register.php
├── register_process.php
├── login.php
├── login_process.php
├── dashboard.php
├── add_product.php
├── add_product_process.php
├── products.php
├── logout.php
├── styles.css
└── README.md
```

## Local Setup Instructions

### 1. Clone the repository
```
git clone https://github.com/USERNAME/REPOSITORY.git
```

### 2. Move project to your web server
- XAMPP → htdocs folder
- WAMP → www folder
- Georgian Server → inside /public_html/yourfolder/

### 3. Import Database

#### admins Table
```
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

#### products Table
```
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2),
  quantity INT,
  image_path VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 4. Configure Database Connection
Edit:
```
/classes/Database.php
```

Update credentials:
```
private $host = "localhost";
private $db   = "your_database";
private $user = "your_username";
private $pass = "your_password";
```

### 5. Run the Project
Local:
```
http://localhost/inventory_project/
```

Georgian server:
```
https://yourserver/~yourusername/inventory_project/
```

## GitHub Requirements
This project includes:
- Proper version control
- Meaningful commit messages
- README.md documentation

## Author
Akarsh Krishna Dinesh  
Computer Programming Student  
Georgian College
