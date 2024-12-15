
DROP DATABASE IF EXISTS educ;
CREATE DATABASE IF NOT EXISTS educ;
USE educ;


CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    photoUrl VARCHAR(255),
    fullName VARCHAR(50),
    email VARCHAR(50),
    password VARCHAR(255),
    program VARCHAR(50),
    yearLevel VARCHAR(3),
    resumePdf VARCHAR(255),
    isCompany BOOLEAN DEFAULT FALSE,
    skills JSON
);

CREATE TABLE company (
    id INT PRIMARY KEY AUTO_INCREMENT,
    photoUrl VARCHAR(255),
    companyName VARCHAR(50),
    email VARCHAR(50),
    password VARCHAR(255),
    website VARCHAR(255),
    address VARCHAR(255)
);

CREATE TABLE internship (
    id INT AUTO_INCREMENT PRIMARY KEY,
    companyId INT,
    company_name VARCHAR(255) NOT NULL,
    internship VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    job_qualification TEXT NOT NULL,
    about_us TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    isApprove BOOLEAN DEFAULT FALSE,
    isRejected BOOLEAN DEFAULT FALSE,
    isDeactivated BOOLEAN DEFAULT FALSE,
    applicants_count INT 
);

CREATE TABLE appliedTo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT,
    internshipId INT
);

ALTER TABLE internship
ADD COLUMN lat DECIMAL(10, 6) NOT NULL,
ADD COLUMN lon DECIMAL(10, 6) NOT NULL;



-- INSERT INTO users (photoUrl, fullName, email, password, program, yearLevel, resumePdf, isCompany, skills)
-- VALUES ("", "John Dave Pega", "jd@gmail.com", "123", "", yearLevel, resumePdf, isCompany, skills) 