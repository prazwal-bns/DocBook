# DocBook [Doctor Appointment System]

🚀🚀 A comprehensive Doctor Appointment System built using Laravel. ⭐⭐

The **Doctor Appointment System** is a Laravel project designed to manage interactions/appoinments between patients, doctor. It allows patients to book appointments, doctors to manage their schedules, and admins to verify doctors and oversee the system.

![GitHub last commit (branch)](https://img.shields.io/github/last-commit/prazwal-bns/DocBook/main)&nbsp;&nbsp;&nbsp;![GitHub Created At](https://img.shields.io/github/created-at/prazwal-bns/DocBook?style=flat-square&logoColor=blue&labelColor=black)&nbsp;&nbsp;&nbsp;![GitHub contributors](https://img.shields.io/github/contributors/prazwal-bns/DocBook?logoColor=Purple&labelColor=black&color=red)&nbsp;&nbsp;&nbsp;![GitHub Repo stars](https://img.shields.io/github/stars/prazwal-bns/DocBook?style=flat-square&labelColor=black&color=yellow)&nbsp;&nbsp;&nbsp;![GitHub issues](https://img.shields.io/github/issues/prazwal-bns/DocBook?labelColor=black&color=orange)&nbsp;&nbsp;&nbsp;![GitHub forks](https://img.shields.io/github/forks/prazwal-bns/DocBook?style=flat-square&labelColor=black&color=green)

<p>
  <img src="https://www.romanianstartups.com/wp-content/uploads/2019/12/docbook-logo.png" alt="DocBook Logo" width="300">
</p>


---

## Database Schema

### 1. Users Table
This table stores general information for all users (patients, doctors, and admins).

| Column Name | Data Type          | Description                                     |
|-------------|--------------------|-------------------------------------------------|
| `user_id`   | INT (PK)           | Primary key, unique ID for each user.           |
| `username`  | VARCHAR(100)       | User's name.                                    |
| `email`     | VARCHAR(100)       | Email address, unique for each user.            |
| `password`  | VARCHAR(255)       | Hashed password.                                |
| `role`      | ENUM('patient', 'doctor', 'admin') | User role.                              |
| `created_at`| TIMESTAMP          | Record creation timestamp.                      |
| `updated_at`| TIMESTAMP          | Record update timestamp.                        |

---

### 2. Patients Table
This table stores additional details specific to patients.

| Column Name     | Data Type    | Description                                      |
|------------------|-------------|--------------------------------------------------|
| `patient_id`     | INT (PK, FK)| References `user_id` from the Users table.       |
| `date_of_birth`  | DATE        | Patient's date of birth.                         |
| `contact_number` | VARCHAR(15) | Patient's contact number.                        |
| `address`        | TEXT        | Patient's address.                               |
| `created_at`     | TIMESTAMP   | Record creation timestamp.                       |
| `updated_at`     | TIMESTAMP   | Record update timestamp.                         |

---

### 3. Doctors Table
This table stores additional details specific to doctors.

| Column Name      | Data Type    | Description                                      |
|-------------------|-------------|--------------------------------------------------|
| `doctor_id`       | INT (PK, FK)| References `user_id` from the Users table.       |
| `specialization_id`| INT (FK)   | References `specialization_id` from Specializations. |
| `contact_number`  | VARCHAR(15) | Doctor's contact number.                         |
| `availability`    | BOOLEAN     | Indicates if the doctor is currently available.  |
| `free_time`       | VARCHAR(50) | Free time slots (e.g., '10 AM - 12 PM').         |
| `created_at`      | TIMESTAMP   | Record creation timestamp.                       |
| `updated_at`      | TIMESTAMP   | Record update timestamp.                         |

---

### 4. Appointments Table
This table stores the appointments booked by patients with doctors.

| Column Name        | Data Type           | Description                                   |
|---------------------|---------------------|-----------------------------------------------|
| `appointment_id`    | INT (PK)           | Primary key, unique ID for each appointment.  |
| `patient_id`        | INT (FK)           | References `patient_id` from the Patients table. |
| `doctor_id`         | INT (FK)           | References `doctor_id` from the Doctors table. |
| `appointment_date`  | DATE               | Date of the appointment.                      |
| `appointment_time`  | TIME               | Time of the appointment.                      |
| `status`            | ENUM('booked', 'completed', 'cancelled') | Appointment status. |
| `created_at`        | TIMESTAMP          | Record creation timestamp.                    |
| `updated_at`        | TIMESTAMP          | Record update timestamp.                      |

---

### 5. Specializations Table
This table lists the specializations available for doctors.

| Column Name        | Data Type    | Description                                    |
|---------------------|-------------|------------------------------------------------|
| `specialization_id` | INT (PK)    | Primary key, unique ID for each specialization.|
| `specialization_name`| VARCHAR(100)| Name of the specialization (e.g., Cardiologist).|
| `created_at`        | TIMESTAMP   | Record creation timestamp.                     |
| `updated_at`        | TIMESTAMP   | Record update timestamp.                       |

---

## Relationships

1. **Users ↔ Patients**: `user_id` in Users is referenced as `patient_id` in Patients.
2. **Users ↔ Doctors**: `user_id` in Users is referenced as `doctor_id` in Doctors.
3. **Doctors ↔ Specializations**: `specialization_id` in Specializations is referenced in Doctors.
4. **Appointments ↔ Patients and Doctors**: `patient_id` references Patients, and `doctor_id` references Doctors.

---

## Features
- Patients can view available doctors, book appointments, and view doctor details.
- Doctors can manage schedules, mark availability, and view patient details for diagnosis.
- Admins can verify doctors and oversee the entire system.

---

## Class Diagram
<img src="https://github.com/user-attachments/assets/0019be7c-4dd5-4770-bcef-3b8e030a46cd" height="500">



### Contributions
Feel free to contribute to this project by submitting issues or pull requests. 😊
