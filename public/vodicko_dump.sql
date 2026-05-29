DROP DATABASE IF EXISTS vodicko;

CREATE DATABASE vodicko;
USE vodicko;

CREATE TABLE `destinations` (
  `destination_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`destination_id`)
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `surname` VARCHAR(45) NOT NULL,
  `username` VARCHAR(45) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `dob` DATE NOT NULL,
  `email` VARCHAR(45) NOT NULL UNIQUE,
  `phone_number` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB;

CREATE TABLE `roles` (
  `role_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB;

CREATE TABLE `tours` (
  `tour_id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `price` INT NOT NULL,
  `capacity` INT NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `thumbnail` VARCHAR(45),
  `description` TEXT,
  PRIMARY KEY (`tour_id`)
) ENGINE=InnoDB;

CREATE TABLE `tour_destination` (
  `destination_id` INT NOT NULL,
  `tour_id` INT NOT NULL,
  PRIMARY KEY (`destination_id`, `tour_id`),
  FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`destination_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`tour_id`) REFERENCES `tours`(`tour_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `user_role` (
  `user_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`role_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `user_tour` (
  `user_id` INT NOT NULL,
  `tour_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `tour_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`tour_id`) REFERENCES `tours`(`tour_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO `destinations` (`name`, `description`) VALUES
('Vodopad Gostilje', 'Prelepi vodopad u blizini Zlatibora.'),
('Uvac Meandri', 'Spektakularni meandri reke Uvac, stanište beloglavog supa.'),
('Pančićev vrh', 'Najviši vrh Kopaonika, idealan za planinarenje.'),
('Mitrovac na Tari', 'Poznato izletište sa bogatom prirodom i šumama.'),
('Topli Do', 'Planinsko selo u srcu Stare planine, poznato po vodopadima.'),
('Ledeni dan', 'Vodopad u selu Dojkinci, popularna atrakcija.'),
('Iriški Venac', 'Popularno izletište na Fruškoj Gori.'),
('Crveni Čot', 'Jedan od najviših vrhova Fruške Gore.');

INSERT INTO `users` (`name`, `surname`, `username`, `password_hash`, `dob`, `email`, `phone_number`) VALUES
('korisnik1', 'korisnik1', 'korisnik1', '$2y$12$48C/oJbwwbP2xPdlKG9ax.8rvurxOYFtrs9HZGq2g0RCSq0pvgBNK', '1990-01-01', 'korisnik1@gmail.com', '+381601111111'),
('Korisnik2', 'Korisnik2', 'korisnik2', '$2y$12$37a/2cojI0f7dkz.JqGpTeXMG4Pg00cWTxpGCXhnsS.9MWyz5FdSK', '1991-02-02', 'korisnik2@gmail.com', '+381602222222'),
('Korisnik3', 'Korisnik3', 'korisnik3', '$2y$12$fqtsTuF3OKVFzxOJ3ZM6a.scrWFQXbg2..UtAgkb45VZkjdjfSc2G', '1992-03-03', 'korisnik3@gmail.com', '+381603333333'),
('menadzer', 'menadzer', 'menadzer', '$2y$12$htqU5UUt9Kxc83QdcRczou8.HGQ6MzTE5GR2ATr/JNhOyLPg2cecW', '2004-09-23', 'menadzer@gmail.com', '+381651234412'),
('Admin', 'Admin', 'admin', '$2y$12$7613jSZFmLb16OH3VjDyVOei0Z6BR1mhWtHIXF2V1P2Dh2hG3AlWO', '1980-05-05', 'admin@gmail.com', '+381604444444');

INSERT INTO `roles` (`name`) VALUES
('admin'),
('manager');

INSERT INTO `tours` (`title`, `price`, `capacity`, `start_date`, `end_date`, `thumbnail`, `description`) VALUES
('Uvac i Gostilje avantura', 120, 20, '2025-10-01', '2025-10-03', 'uvac.jpg', 'Otkrijte čari kanjona Uvca i selo Gostilje uz nezaboravnu avanturu i prirodne lepote.'),
('Osvojimo Pančićev vrh', 100, 15, '2025-09-20', '2025-09-22', 'pancicev_vrh.jpg', 'Planinarenje do Pančićevog vrha sa spektakularnim pogledima i čistim planinskim vazduhom.'),
('Tara prirodni raj', 90, 25, '2025-09-18', '2025-09-20', 'tara.jpg', 'Uživajte u netaknutoj prirodi Tare, šumama, rekama i mirnom ambijentu.'),
('Stara planina vodopadi', 110, 1, '2025-09-25', '2025-09-27', 'stara_planina_vodopadi.jpg', 'Posetite veličanstvene vodopade Stare planine i doživite prirodnu čaroliju.'),
('Fruška Gora ekspedicija', 80, 30, '2025-10-05', '2025-10-06', 'fruska_gora.jpg', 'Ekspedicija kroz vinograde, šume i istorijske manastire Fruške Gore.'),
('Đerdap i Golubac avantura', 130, 20, '2025-10-10', '2025-10-12', 'djerdap.jpg', 'Otkrijte Đerdap i srednjovekovni Golubački grad uz nezaboravne pejzaže i istorijske znamenitosti.'),
('Kopaonik zimska čarolija', 150, 25, '2025-12-15', '2025-12-20', 'kopaonik.jpg', 'Zimski odmor na Kopaoniku sa skijanjem, snegom i planinskim užicima.'),
('Sokobanja relaks avantura', 85, 15, '2025-11-05', '2025-11-07', 'sokobanja.jpg', 'Relaks u Sokobanji: banje, šetnje i prirodne lepote ovog popularnog odredišta.'),
('Rtanj planinska ekspedicija', 120, 10, '2025-09-30', '2025-10-02', 'rtanj.jpg', 'Planinarenje do Rtnja uz spektakularne vidike i mističnu atmosferu planine.');

INSERT INTO `tour_destination` (`destination_id`, `tour_id`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 4),
(7, 5),
(8, 5);

INSERT INTO `user_role`(`user_id`, `role_id`) VALUES
(4, 2),
(5, 1);