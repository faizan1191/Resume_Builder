-- Resume Data Table Structure
CREATE TABLE resume_data (
    id int NOT NULL AUTO_INCREMENT,
    user_id int,
    name varchar(255),
    email varchar(255),
    phone varchar(255),
    address varchar(255),
    linkedin varchar(255),
    github varchar(255),
    instagram varchar(255),
    career_objective text,
    profile_pic varchar(255),
    language varchar(255),
    interest varchar(255),
    skill varchar(255),
    title varchar(255),
    PRIMARY KEY (id)
);

-- Resume Users Table Structure
CREATE TABLE resume_users (
    id int NOT NULL AUTO_INCREMENT,
    user_name varchar(255),
    email varchar(255),
    password varchar(255),
    PRIMARY KEY (id)
);

-- Resume Experiences Table Structure
CREATE TABLE resume_experiences (
    id int NOT NULL AUTO_INCREMENT,
    resume_id int NOT NULL,
    position varchar(255),
    company varchar(255),
    duration varchar(255),
    PRIMARY KEY (id)
);

-- Resume Education Table Structure
CREATE TABLE resume_education (
    id int NOT NULL AUTO_INCREMENT,
    resume_id int NOT NULL,
    course varchar(255),
    college varchar(255),
    percentage varchar(255),
    duration varchar(255),
    PRIMARY KEY (id)
);

-- Resume Certificates Table Structure
CREATE TABLE resume_certificates (
    id int NOT NULL AUTO_INCREMENT,
    resume_id int NOT NULL,
    title varchar(255),
    description text,
    date varchar(255),
    PRIMARY KEY (id)
);

-- Resume Projects Table Structure
CREATE TABLE resume_projects (
    id int NOT NULL AUTO_INCREMENT,
    resume_id int NOT NULL,
    title varchar(255),
    description text,
    PRIMARY KEY (id)
);

-- Adding Constraints in table `resume_data`
ALTER TABLE resume_data
ADD CONSTRAINT FK_ResumeUser
FOREIGN KEY (user_id) REFERENCES resume_users(id);

-- Adding Constraints in table `resume_experiences`
ALTER TABLE resume_experiences
ADD CONSTRAINT FK_ExperienceResume
FOREIGN KEY (resume_id) REFERENCES resume_data(id);

-- Adding Constraints in table `resume_education`
ALTER TABLE resume_education
ADD CONSTRAINT FK_EducationResume
FOREIGN KEY (resume_id) REFERENCES resume_data(id);

-- Adding Constraints in table `resume_certificates`
ALTER TABLE resume_certificates
ADD CONSTRAINT FK_CertificateResume
FOREIGN KEY (resume_id) REFERENCES resume_data(id);

-- Adding Constraints in table `resume_projects`
ALTER TABLE resume_projects
ADD CONSTRAINT FK_ProjectResume
FOREIGN KEY (resume_id) REFERENCES resume_data(id);