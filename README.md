# DocBook [Doctor Appointment System]

🚀🚀 A comprehensive Doctor Appointment System built using Laravel. ⭐⭐

The **Doctor Appointment System** is a Laravel project designed to manage interactions/appoinments between patients, doctor. It allows patients to book appointments, doctors to manage their schedules, and admins to verify doctors and oversee the system.

![GitHub last commit (branch)](https://img.shields.io/github/last-commit/prazwal-bns/DocBook/main)&nbsp;&nbsp;&nbsp;![GitHub Created At](https://img.shields.io/github/created-at/prazwal-bns/DocBook?style=flat-square&logoColor=blue&labelColor=black)&nbsp;&nbsp;&nbsp;![GitHub contributors](https://img.shields.io/github/contributors/prazwal-bns/DocBook?logoColor=Purple&labelColor=black&color=red)&nbsp;&nbsp;&nbsp;![GitHub Repo stars](https://img.shields.io/github/stars/prazwal-bns/DocBook?style=flat-square&labelColor=black&color=yellow)&nbsp;&nbsp;&nbsp;![GitHub issues](https://img.shields.io/github/issues/prazwal-bns/DocBook?labelColor=black&color=orange)&nbsp;&nbsp;&nbsp;![GitHub forks](https://img.shields.io/github/forks/prazwal-bns/DocBook?style=flat-square&labelColor=black&color=green)

<p>
  <img src="https://www.romanianstartups.com/wp-content/uploads/2019/12/docbook-logo.png" alt="DocBook Logo" width="300">
</p>


---

## Database Schema

# Database Design for Doctor Appointment System

## 1. Users Table

| Column Name        | Data Type  | Constraints                          |
|--------------------|------------|--------------------------------------|
| `id`               | BIGINT (PK)| Auto Increment                      |
| `name`             | STRING     | Not Null                            |
| `email`            | STRING     | Unique, Not Null                    |
| `role`             | ENUM       | Default: 'patient'                  |
| `address`          | STRING     | Nullable                            |
| `phone`            | STRING     | Nullable                            |
| `email_verified_at`| TIMESTAMP  | Nullable                            |
| `password`         | STRING     | Not Null                            |
| `remember_token`   | STRING     | Nullable                            |
| `created_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |
| `updated_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |

---

## 2. Patients Table

| Column Name        | Data Type  | Constraints                          |
|--------------------|------------|--------------------------------------|
| `id`               | BIGINT (PK)| Auto Increment                      |
| `user_id`          | BIGINT (FK)| References `users.id`, Cascade On Delete |
| `gender`           | STRING     | Not Null                            |
| `dob`              | DATE       | Not Null                            |
| `created_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |
| `updated_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |

---

## 3. Doctors Table

| Column Name        | Data Type  | Constraints                          |
|--------------------|------------|--------------------------------------|
| `id`               | BIGINT (PK)| Auto Increment                      |
| `user_id`          | BIGINT (FK)| References `users.id`, Cascade On Delete |
| `specialization_id`| BIGINT (FK)| References `specializations.id`, Cascade On Delete |
| `status`           | ENUM       | Default: 'available'                |
| `bio`              | TEXT       | Not Null                            |
| `created_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |
| `updated_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |

---

## 4. Appointments Table

| Column Name        | Data Type  | Constraints                          |
|--------------------|------------|--------------------------------------|
| `id`               | BIGINT (PK)| Auto Increment                      |
| `patient_id`       | BIGINT (FK)| References `patients.id`, Cascade On Delete |
| `doctor_id`        | BIGINT (FK)| References `doctors.id`, Cascade On Delete |
| `appointment_date` | DATE       | Not Null                            |
| `start_time`       | TIME       | Not Null                            |
| `end_time`         | TIME       | Not Null                            |
| `created_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |
| `updated_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |

---

## 5. Schedules Table

| Column Name        | Data Type  | Constraints                          |
|--------------------|------------|--------------------------------------|
| `id`               | BIGINT (PK)| Auto Increment                      |
| `doctor_id`        | BIGINT (FK)| References `doctors.id`, Cascade On Delete |
| `day`              | ENUM       | Not Null                            |
| `start_time`       | TIME       | Not Null                            |
| `end_time`         | TIME       | Not Null                            |
| `created_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |
| `updated_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |

---

## 6. Specializations Table

| Column Name        | Data Type  | Constraints                          |
|--------------------|------------|--------------------------------------|
| `id`               | BIGINT (PK)| Auto Increment                      |
| `name`             | STRING     | Not Null                            |
| `created_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |
| `updated_at`       | TIMESTAMP  | Default: CURRENT_TIMESTAMP          |


# Database Relationships

## Users and Patients

- **One-to-One**:  
  Each `user` can be associated with exactly one `patient` record.  
  - **Foreign Key**: `patients.user_id` → `users.id`

---

## Users and Doctors

- **One-to-One**:  
  Each `user` can be associated with exactly one `doctor` record.  
  - **Foreign Key**: `doctors.user_id` → `users.id`

---

## Doctors and Specializations

- **Many-to-One**:  
  Each `doctor` belongs to one `specialization`, but a `specialization` can have many `doctors`.  
  - **Foreign Key**: `doctors.specialization_id` → `specializations.id`

---

## Patients and Appointments

- **One-to-Many**:  
  A `patient` can have multiple `appointments`, but each `appointment` belongs to one `patient`.  
  - **Foreign Key**: `appointments.patient_id` → `patients.id`

---

## Doctors and Appointments

- **One-to-Many**:  
  A `doctor` can have multiple `appointments`, but each `appointment` belongs to one `doctor`.  
  - **Foreign Key**: `appointments.doctor_id` → `doctors.id`

---

## Doctors and Schedules

- **One-to-Many**:  
  A `doctor` can have multiple `schedules`, but each `schedule` belongs to one `doctor`.  
  - **Foreign Key**: `schedules.doctor_id` → `doctors.id`


---

## Features
- Patients can view available doctors, book appointments, and view doctor details.
- Doctors can manage schedules, mark availability, and view patient details for diagnosis.
- Admins can verify doctors and oversee the entire system.

---

## Class Diagram

![classdiagram](https://github.com/user-attachments/assets/d6cecd07-9ec4-420b-86a2-b41da7f9a1f1)


### Contributions
Feel free to contribute to this project by submitting issues or pull requests. 😊
