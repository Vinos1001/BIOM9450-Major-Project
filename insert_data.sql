INSERT INTO Patient (
        Name,
        DOB,
        Sex,
        PhoneNumber,
        Address,
        DiagnosticInformation,
        GeneticMutations
    )
VALUES (
        'John Doe',
        '1980-01-01',
        'Male',
        '1234567890',
        '123 Main St',
        'Hypertension',
        'BRCA1'
    ),
    (
        'Jane Smith',
        '1990-02-02',
        'Female',
        '2345678901',
        '456 Elm St',
        'Diabetes',
        'BRCA2'
    ),
    (
        'Alice Johnson',
        '1985-03-03',
        'Female',
        '3456789012',
        '789 Oak St',
        'Asthma',
        'TP53'
    ),
    (
        'Bob Brown',
        '1975-04-04',
        'Male',
        '4567890123',
        '101 Pine St',
        'Cardiomyopathy',
        'APC'
    ),
    (
        'Charlie Davis',
        '2000-05-05',
        'Other',
        '5678901234',
        '202 Cedar St',
        'Migraine',
        'PTEN'
    );
INSERT INTO Phenotypes (Description, PatientID, DateRecorded)
VALUES ('Tall stature', 1, '2024-10-01'),
    ('Blue eyes', 2, '2024-10-02'),
    ('Blonde hair', 3, '2024-10-03'),
    ('Left-handed', 4, '2024-10-04'),
    ('Freckles', 5, '2024-10-05');
INSERT INTO Mutations (
        GeneInvolved,
        MutationType,
        ImpactOnHealth,
        PatientID
    )
VALUES ('BRCA1', 'Missense', 'Breast cancer risk', 1),
    ('BRCA2', 'Nonsense', 'Ovarian cancer risk', 2),
    ('TP53', 'Frameshift', 'Li-Fraumeni syndrome', 3),
    (
        'APC',
        'Splice site',
        'Familial adenomatous polyposis',
        4
    ),
    ('PTEN', 'Insertion', 'Cowden syndrome', 5);
INSERT INTO Diagnostics (DiagnosisType, DateOfDiagnosis, PatientID)
VALUES ('Hypertension', '2024-10-10', 1),
    ('Diabetes', '2024-10-11', 2),
    ('Asthma', '2024-10-12', 3),
    ('Cardiomyopathy', '2024-10-13', 4),
    ('Migraine', '2024-10-14', 5);