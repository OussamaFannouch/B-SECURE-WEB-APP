-- Query to create the required database and tables for the B-Secure application
-- create the required "users" table on the database for the B-Secure application
CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) DEFAULT NULL,
    role ENUM('admin', 'member') DEFAULT 'member',
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY (email)
);

-- create the required "meetings" table on the database for the B-Secure application
CREATE TABLE meetings (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    description TEXT NULL,
    PRIMARY KEY (id)
);

-- create the required "registrations" table on the database for the B-Secure application
CREATE TABLE registrations (
    id INT(11) NOT NULL AUTO_INCREMENT,
    meeting_id INT(11) NOT NULL,
    email VARCHAR(255) NOT NULL,
    registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE (meeting_id, email),
    FOREIGN KEY (meeting_id) REFERENCES meetings(id) ON DELETE CASCADE ON UPDATE CASCADE
);