CREATE DATABASE IF NOT EXISTS quiz_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE quiz_db;

DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option1 VARCHAR(100) NOT NULL,
    option2 VARCHAR(100) NOT NULL,
    option3 VARCHAR(100) NOT NULL,
    option4 VARCHAR(100) NOT NULL,
    answer VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO questions (question, option1, option2, option3, option4, answer) VALUES
('What does HTML stand for?', 'Hyper Text Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyperlinks and Text Makeup Language', 'Hyper Text Markup Language'),
('Which tag is used for largest heading in HTML?', '<h6>', '<h1>', '<head>', '<title>', '<h1>'),
('Which CSS property changes text color?', 'font-style', 'text-align', 'color', 'background', 'color'),
('Which HTML tag is used to create a form?', '<input>', '<form>', '<fieldset>', '<label>', '<form>'),
('Which HTML element is used to create a hyperlink?', '<a>', '<link>', '<href>', '<nav>', '<a>'),
('Which HTML tag is used to insert an image?', '<picture>', '<img>', '<src>', '<media>', '<img>'),
('Which HTML attribute provides alternative text for an image?', 'title', 'alt', 'name', 'caption', 'alt'),
('Which CSS property controls the size of text?', 'text-style', 'font-size', 'font-weight', 'text-size', 'font-size'),
('Which CSS property makes text bold?', 'font-family', 'font-style', 'font-weight', 'text-decoration', 'font-weight'),
('Which CSS value centers a block element horizontally when width is set?', 'padding: auto', 'margin: auto', 'text-align: center', 'position: center', 'margin: auto'),
('Which CSS property is used to add space inside an element?', 'margin', 'spacing', 'padding', 'border-spacing', 'padding'),
('Which CSS property rounds the corners of a box?', 'corner-radius', 'border-radius', 'box-round', 'radius', 'border-radius'),
('Which CSS layout module is best for arranging items in one row or one column?', 'Float', 'Table', 'Flexbox', 'Positioning', 'Flexbox'),
('Which JavaScript keyword is used to declare a block-scoped variable?', 'var', 'let', 'define', 'constvar', 'let'),
('Which JavaScript keyword declares a constant?', 'let', 'value', 'fixed', 'const', 'const'),
('Which JavaScript method writes a message to the browser console?', 'console.print()', 'log.console()', 'console.log()', 'print.console()', 'console.log()'),
('Which JavaScript function is used to show an alert box?', 'message()', 'alert()', 'prompt()', 'console()', 'alert()'),
('How do you write a single-line comment in JavaScript?', '<!-- comment -->', '# comment', '// comment', '/* comment */', '// comment'),
('Which HTML tag is used to include JavaScript in a page?', '<javascript>', '<script>', '<js>', '<code>', '<script>'),
('Which JavaScript operator checks both value and type?', '=', '==', '===', '!=', '===');
