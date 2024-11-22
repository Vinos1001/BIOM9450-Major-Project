-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 08:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `patient_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `clinician`
--

CREATE TABLE `clinician` (
  `ClinicianID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Specialty` varchar(100) DEFAULT NULL,
  `ContactInfo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `clinician`
--

INSERT INTO `clinician` (`ClinicianID`, `Name`, `Username`, `PasswordHash`, `Specialty`, `ContactInfo`) VALUES
(1, 'Ben', 'User', 'test', 'doc', 'unkown');

-- --------------------------------------------------------

--
-- Table structure for table `diagnostics`
--

CREATE TABLE `diagnostics` (
  `DiagnosisID` int(11) NOT NULL,
  `DiagnosisType` varchar(100) NOT NULL,
  `DateOfDiagnosis` date DEFAULT NULL,
  `PatientID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mutationvariants`
--

CREATE TABLE `mutationvariants` (
  `MutationID` int(11) NOT NULL,
  `GeneInvolved` varchar(100) NOT NULL,
  `MutationType` varchar(100) DEFAULT NULL,
  `HealthImpact` text DEFAULT NULL,
  `PatientID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PatientID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `DOB` date NOT NULL,
  `Sex` enum('Male','Female') NOT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `DiagnosticInformation` text DEFAULT NULL,
  `GeneticMutations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PatientID`, `Name`, `DOB`, `Sex`, `PhoneNumber`, `Address`, `DiagnosticInformation`, `GeneticMutations`) VALUES
(3, 'tesxr', '2008-04-08', 'Male', '9767', 'lp', 'm', 'j');

-- --------------------------------------------------------

--
-- Table structure for table `phenotypes`
--

CREATE TABLE `phenotypes` (
  `PhenotypeID` int(11) NOT NULL,
  `Description` text NOT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `DateRecorded` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `useractivity`
--

CREATE TABLE `useractivity` (
  `ActivityID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ActivityType` varchar(50) DEFAULT NULL,
  `ActivityTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `IPAddress` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clinician`
--
ALTER TABLE `clinician`
  ADD PRIMARY KEY (`ClinicianID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `diagnostics`
--
ALTER TABLE `diagnostics`
  ADD PRIMARY KEY (`DiagnosisID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `mutationvariants`
--
ALTER TABLE `mutationvariants`
  ADD PRIMARY KEY (`MutationID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`);

--
-- Indexes for table `phenotypes`
--
ALTER TABLE `phenotypes`
  ADD PRIMARY KEY (`PhenotypeID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `useractivity`
--
ALTER TABLE `useractivity`
  ADD PRIMARY KEY (`ActivityID`),
  ADD KEY `UserID` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clinician`
--
ALTER TABLE `clinician`
  MODIFY `ClinicianID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `diagnostics`
--
ALTER TABLE `diagnostics`
  MODIFY `DiagnosisID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mutationvariants`
--
ALTER TABLE `mutationvariants`
  MODIFY `MutationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phenotypes`
--
ALTER TABLE `phenotypes`
  MODIFY `PhenotypeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `useractivity`
--
ALTER TABLE `useractivity`
  MODIFY `ActivityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diagnostics`
--
ALTER TABLE `diagnostics`
  ADD CONSTRAINT `diagnostics_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`);

--
-- Constraints for table `mutationvariants`
--
ALTER TABLE `mutationvariants`
  ADD CONSTRAINT `mutationvariants_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`);

--
-- Constraints for table `phenotypes`
--
ALTER TABLE `phenotypes`
  ADD CONSTRAINT `phenotypes_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`);

--
-- Constraints for table `useractivity`
--
ALTER TABLE `useractivity`
  ADD CONSTRAINT `useractivity_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `clinician` (`ClinicianID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
