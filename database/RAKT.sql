DROP DATABASE IF EXISTS RAKT;

CREATE DATABASE IF NOT EXISTS RAKT;

USE RAKT;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `admin` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `pincode` bigint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `donor` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pincode` bigint(6) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `blood` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `patient` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pincode` bigint(6) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `blood` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `blood` (
  `id` bigint(20) NOT NULL,
  `AP` bigint(20) ,
  `AN` bigint(20) ,
  `BP` bigint(20) ,
  `BN` bigint(20) ,
  `ABP` bigint(20) ,
  `ABN` bigint(20) ,
  `OP` bigint(20) ,
  `ON` bigint(20) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

insert into blood( `id`,`AP`,`AN`,`BP`,`BN`,`ABP`,`ABN`,`OP`,`ON`) values (1,0,0,0,0,0,0,0,0);

CREATE TABLE `donate` (
  `id` bigint(20) NOT NULL,
  `donor_id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `disease` varchar(255) NOT NULL,
  `blood` varchar(10) NOT NULL,
  `unit` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `hospital1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `request` (
  `id` bigint(20) NOT NULL,
  `patient_id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `blood` varchar(10) NOT NULL,
  `unit` bigint(20) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `hospital1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `locations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `location1` VARCHAR(255),
    `hospital1` VARCHAR(255),
    `address1` VARCHAR(255),
    `state1` VARCHAR(255),
    `district1` VARCHAR(255),
    `pincode` bigint(6)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `info` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `location1` VARCHAR(255),
    `state1` VARCHAR(255),
    `district1` VARCHAR(255),
    `pincode` bigint(6)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `blood`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `donate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_donate_donor` (`donor_id`);

ALTER TABLE `donor`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_request_patient` (`patient_id`);

ALTER TABLE `admin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `blood`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `donate`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `donor`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `patient`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;


ALTER TABLE `request`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `donate`
  ADD CONSTRAINT `fk_donate_donor` FOREIGN KEY (`donor_id`) REFERENCES `donor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `request`
  ADD CONSTRAINT `fk_request_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

LOAD DATA INFILE 'C:\\xampp\\htdocs\\RAKT\\database\\hospital_directory.csv'
INTO TABLE locations
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@Location,@Hospital,@Address,@State,@District,@Pincode)
SET   location1 = @Location, hospital1 = @Hospital, address1= @Address, state1 = @State, district1 = @District, pincode = @Pincode;

LOAD DATA INFILE 'C:\\xampp\\htdocs\\RAKT\\database\\pincodes.csv'
INTO TABLE info
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@Location,@State,@District,@Pincode)
SET   location1 = @Location, state1 = @State, district1 = @District, pincode = @Pincode;
