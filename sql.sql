-- Create the database
CREATE DATABASE school_management_system;
USE school_management_system;

-- Classes table
CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    teacher_id INT,
    UNIQUE KEY unique_class_name (class_name)
);

-- Teachers table
CREATE TABLE teachers (
    teacher_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    salary DECIMAL(10,2) NOT NULL,
    background_check BOOLEAN DEFAULT FALSE,
    hire_date DATE NOT NULL
);

-- Parents table
CREATE TABLE parents (
    parent_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    relationship VARCHAR(50) NOT NULL
);

-- Pupils table
CREATE TABLE pupils (
    pupil_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    address TEXT NOT NULL,
    medical_info TEXT,
    class_id INT,
    parent1_id INT,
    parent2_id INT,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE SET NULL,
    FOREIGN KEY (parent1_id) REFERENCES parents(parent_id) ON DELETE SET NULL,
    FOREIGN KEY (parent2_id) REFERENCES parents(parent_id) ON DELETE SET NULL
);

-- Teaching Assistants table
CREATE TABLE teaching_assistants (
    ta_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    salary DECIMAL(10,2) NOT NULL,
    background_check BOOLEAN DEFAULT FALSE,
    hire_date DATE NOT NULL,
    class_id INT,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE SET NULL
);

-- Library Books table
CREATE TABLE library_books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    author VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) NOT NULL,
    published_year INT NOT NULL,
    available BOOLEAN DEFAULT TRUE
);

-- Book Loans table
CREATE TABLE book_loans (
    loan_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    pupil_id INT NOT NULL,
    loan_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    FOREIGN KEY (book_id) REFERENCES library_books(book_id),
    FOREIGN KEY (pupil_id) REFERENCES pupils(pupil_id)
);

-- Dinner Money table
CREATE TABLE dinner_money (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    pupil_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    FOREIGN KEY (pupil_id) REFERENCES pupils(pupil_id)
);

-- Add foreign key to classes table after teachers table exists
ALTER TABLE classes 
ADD CONSTRAINT fk_teacher
FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id) ON DELETE SET NULL;