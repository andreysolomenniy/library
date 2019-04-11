USE library;
SET CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS book (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS author (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS book_authors (id_book INT NOT NULL, id_author INT NOT NULL, PRIMARY KEY (id_book, id_author));

INSERT INTO author (name) VALUES ('Брайан Керниган');
INSERT INTO author (name) VALUES ('Деннис Ритчи');
INSERT INTO author (name) VALUES ('Карл Маркс');
INSERT INTO author (name) VALUES ('Роб Пайк');

INSERT INTO book (name) VALUES ('Язык программирования Си');
INSERT INTO book (name) VALUES ('Капитал');
INSERT INTO book (name) VALUES ('Практика программирования');

INSERT INTO book_authors (id_book, id_author) VALUES ((SELECT id FROM book WHERE name = 'Язык программирования Си'), (SELECT id FROM author WHERE name = 'Брайан Керниган'));
INSERT INTO book_authors (id_book, id_author) VALUES ((SELECT id FROM book WHERE name = 'Язык программирования Си'), (SELECT id FROM author WHERE name = 'Деннис Ритчи'));
INSERT INTO book_authors (id_book, id_author) VALUES ((SELECT id FROM book WHERE name = 'Практика программирования'), (SELECT id FROM author WHERE name = 'Брайан Керниган'));
INSERT INTO book_authors (id_book, id_author) VALUES ((SELECT id FROM book WHERE name = 'Практика программирования'), (SELECT id FROM author WHERE name = 'Роб Пайк'));
INSERT INTO book_authors (id_book, id_author) VALUES ((SELECT id FROM book WHERE name = 'Капитал'), (SELECT id FROM author WHERE name = 'Карл Маркс'));
