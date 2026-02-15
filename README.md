# Doc Book [Doctor Appointment System]

🚀🚀 A comprehensive Doctor Appointment Application built using Laravel. ⭐⭐

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
| `status`           | ENUM       | Default: pending                    |
| `day`              | ENUM       | Default: pending                    |
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
| `status`           | ENUM       | Default: available                  |
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

---

## 7. Reviews Table

| Column Name        | Data Type      | Constraints                          |
|--------------------|----------------|--------------------------------------|
| `id`               | BIGINT (PK)    | Auto Increment                       |
| `appointment_id`   | BIGINT (FK)    | Foreign Key                          |
| `review_msg`       | TEXT           | Not Null                             |
| `created_at`       | TIMESTAMP      | Default: CURRENT_TIMESTAMP           |
| `updated_at`       | TIMESTAMP      | Default: CURRENT_TIMESTAMP           |


## Relationships Between Models

### 1. User
- **Has One:** 
  - `Patient`  
    ```php
    public function patient() {
        return $this->hasOne(Patient::class);
    }
    ```
  - `Doctor`  
    ```php
    public function doctor() {
        return $this->hasOne(Doctor::class);
    }
    ```

---

### 2. Patient
- **Belongs To:** 
  - `User`  
    ```php
    public function user() {
        return $this->belongsTo(User::class);
    }
    ```
- **Has Many:** 
  - `Appointment`  
    ```php
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
    ```

---

### 3. Doctor
- **Belongs To:** 
  - `User`  
    ```php
    public function user() {
        return $this->belongsTo(User::class);
    }
    ```
  - `Specialization`  
    ```php
    public function specialization() {
        return $this->belongsTo(Specialization::class);
    }
    ```
- **Has Many:** 
  - `Appointment`  
    ```php
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
    ```
  - `Schedule`  
    ```php
    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
    ```

---

### 4. Appointment
- **Belongs To:** 
  - `Doctor`  
    ```php
    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }
    ```
  - `Patient`  
    ```php
    public function patient() {
        return $this->belongsTo(Patient::class);
    }
    ```
- **Has One:** 
  - `Review`  
    ```php
    public function review() {
        return $this->hasOne(Review::class, 'appointment_id', 'id');
    }
    ```

---

### 5. Schedule
- **Belongs To:** 
  - `Doctor`  
    ```php
    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }
    ```
- **Has Many:** 
  - `Appointment`  
    ```php
    public function appointments() {
        return $this->hasMany(Appointment::class, 'schedule_id'); // Replace 'schedule_id' if the foreign key is named differently.
    }
    ```

---

### 6. Specialization
- **Has Many:** 
  - `Doctor`  
    ```php
    public function doctors() {
        return $this->hasMany(Doctor::class);
    }
    ```

---

### 7. Review
- **Belongs To:** 
  - `Appointment`  
    ```php
    public function appointment() {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }
    ```


---

# Doctor Appointment Management System

An efficient and user-friendly platform to streamline the appointment process between doctors and patients. This system ensures seamless scheduling, diagnosis management, and system oversight.

---

## Features

### 🌟 **For Patients**
- Browse available doctors by specialization.
- Book appointments with doctors at your convenience.
- Access a history of past and upcoming appointments.

### 🩺 **For Doctors**
- Manage schedules and set availability easily.
- View and manage patient details for diagnosis and treatment.
- Receive emails for upcoming appointments and changes.

### 🛠 **For Admins**
- Oversee and manage all system activities.
- Manage user accounts, ensuring system integrity.
- Manage [Add, Remove, Edit] specialization.

### 🔍 **Other Features**
- Secure login and authentication for all users.
- Review system for patients to provide feedback on their appointments.
- Efficient and flexible system for booking appointments.

---

## Tech Stack
- **Frontend:** HTML, Tailwind CSS, JavaScript
- **Backend:** PHP Laravel
- **Database:** SQLite
- **Other Tools:** Blade Templates

---

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/prazwal-bns/DocBook.git
   cd DocBook
   

---

## Class Diagram
![updatedClassDiagram](https://github.com/user-attachments/assets/9d504b97-289d-482a-b58f-5336a5e0a98b)



### Contributions
Feel free to contribute to this project by submitting issues or pull requests. 😊
