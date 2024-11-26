CREATE TABLE Patient (
    PatientID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    DOB DATE NOT NULL,
    Sex VARCHAR(10) CHECK (SEX IN ('Male', 'Female', 'Other')),
    PhoneNumber VARCHAR(15),
    Address VARCHAR(255),
    DiagnosticInformation TEXT,
    GeneticMutations TEXT
);
CREATE TABLE Clinician (
    ClinicianID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Specialty VARCHAR(100),
    ContactInformation VARCHAR(100)
);
CREATE TABLE Phenotypes (
    PhenotypeID INT PRIMARY KEY AUTO_INCREMENT,
    Description VARCHAR(255) NOT NULL,
    PatientID INT NOT NULL,
    DateRecorded DATE NOT NULL,
    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID)
);
CREATE TABLE Mutations (
    MutationID INT PRIMARY KEY AUTO_INCREMENT,
    GeneInvolved VARCHAR(100) NOT NULL,
    MutationType VARCHAR(100) NOT NULL,
    ImpactOnHealth TEXT,
    PatientID INT NOT NULL,
    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID)
);
CREATE TABLE Diagnostics (
    DiagnosisID INT PRIMARY KEY AUTO_INCREMENT,
    DiagnosisType VARCHAR(100) NOT NULL,
    DateOfDiagnosis DATE NOT NULL,
    PatientID INT NOT NULL,
    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID)
);
CREATE TABLE AuditLog (
    LogID INT PRIMARY KEY AUTO_INCREMENT,
    ClinicianID INT NOT NULL,
    Action VARCHAR(255) NOT NULL,
    ActionTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PatientID INT DEFAULT NULL,
    Description TEXT,
    FOREIGN KEY (ClinicianID) REFERENCES Clinician(ClinicianID),
    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID)
);