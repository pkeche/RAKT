use RAKT;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `admin` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `admin` (`id`, `name`, `username`, `email`, `pwd`) VALUES
(007, 'Prathamesh', 'PK', 'PK@gmail.com', 'PK');


CREATE TABLE `blood` (
  `id` bigint(20) NOT NULL,
  `AP` bigint(20) DEFAULT NULL,
  `AN` bigint(20) DEFAULT NULL,
  `BP` bigint(20) DEFAULT NULL,
  `BN` bigint(20) DEFAULT NULL,
  `ABP` bigint(20) DEFAULT NULL,
  `ABN` bigint(20) DEFAULT NULL,
  `OP` bigint(20) DEFAULT NULL,
  `ON` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `blood` (`id`, `AP`, `AN`, `BP`, `BN`, `ABP`, `ABN`, `OP`, `ON`) VALUES
(1, 0, 0, 0, 0, 0, 0, 0, 0);


CREATE TABLE `donate` (
  `id` bigint(20) NOT NULL,
  `donor_id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `disease` varchar(255) NOT NULL,
  `blood` varchar(10) NOT NULL,
  `unit` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `donor` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pincode` int(6) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `blood` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `patient` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pincode` int(6) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `blood` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `request` (
  `id` bigint(20) NOT NULL,
  `patient_id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `blood` varchar(10) NOT NULL,
  `unit` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state VARCHAR(255),
    district VARCHAR(255),
    location VARCHAR(255),
    pincode int(6)
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

LOAD DATA INFILE 'database\\pincodes.csv'
INTO TABLE locations
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@State, @District, @Location, @Unnamed_3, @Pincode)
SET state = @State, district = @District, location = @Location, pincode = NULLIF(@Pincode, '');
