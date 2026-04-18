Student 1:
Ahmad Ghazi
Student 2:
Hadi Karim
Student 3:
Hadi Araman
Project Title
What is the name of your project? Task-Management
Project Description
Briefly describe the objective and scope of your project. What problem does it solve or what functionality will it provide?
This project involves the development of a web-based task management application designed to organize and facilitate the distribution of work among different actors within a structured system. The main objective of the system is to offer a centralized platform enabling the creation, assignment, tracking, and validation of tasks in a clear, secure, and efficient manner.
The system is based on three main roles: the Administrator (Admin), the Manager, and the User.
The administrator plays a central role in the overall management of the platform. They are responsible for validating manager accounts before activation, managing users and managers, as well as exercising general control over the system. This step ensures account reliability and prevents unauthorized access to sensitive features.
The manager is responsible for creating and managing tasks. When creating a task, they define essential information such as the description, required skills, financial reward (bounty), and the completion deadline. Published tasks become visible to users with the corresponding skills. The manager can track the progress of their tasks, view the users working on them, and validate or reject delivered tasks.
The user, for their part, can browse available tasks, directly accept a task, and commit to completing it within the allotted time. Once the task is finished, they deliver it through the system. After validation by the manager, the user receives the reward associated with the task. The user can also manage their skills, view their activity history, and track their earnings.
The system operates based on a clear workflow, from task creation through to final validation and reward assignment. The separation of roles allows for a precise distribution of responsibilities and ensures better organization of work within the platform.
The project aims to address several current challenges, including the difficulty of organizing remote work, the lack of clear task tracking, and the absence of simple systems for managing rewards and validating completed work. By centralizing these features in a single platform, the system improves productivity, transparency, and communication among the different actors.
Finally, the application is designed to be adaptable to a professional or organizational environment, making it usable not only for individual micro-tasks, but also as an internal task management system within a company, enabling better organization of work among administrators, managers, and employees.
Target Audience
Who will benefit from or use this application? Identify the targeted users.
This application is aimed at several categories of users who may benefit from a structured task management system, both in a remote work context and in an organized professional environment.
The first target audience consists of university students who want to gain practical experience by completing concrete tasks, while balancing their work with their studies. The platform allows them to develop their skills, manage their time, and benefit from a clear validation and reward system.
The project also targets freelancers and independent workers looking for short-term, one-off assignments. Thanks to the task, deadline, and validation system, these users can accept missions suited to their skills and transparently track the status of their work and earnings.
Another important audience includes owners of small and medium-sized businesses as well as managers who need to delegate certain tasks while maintaining control over their execution. The platform provides them with an effective tool to publish tasks, track their progress, validate deliveries, and manage associated rewards.
Finally, the system is designed to be adaptable to a professional or organizational environment, enabling its use as an internal task management system within a company. In this context, the roles of administrator, manager, and user facilitate work distribution, performance tracking, and access management, making the platform relevant for larger-scale use.
Thanks to this diversity of profiles, the platform caters to a broad audience and can evolve according to the needs of users and organizations.
Key Features
List the main features your project will implement (e.g., authentication, CRUD operations, search functionality, role-based access control).
The system implements a set of essential features enabling complete and structured task management. These features are organized according to system roles in order to ensure a clear separation of responsibilities and efficient use of the platform.
4.1 Authentication and Account Management
The system integrates a secure authentication mechanism allowing users to access the platform through two main features: Login and Sign Up. Each user must have a personal account to ensure data confidentiality and protect sensitive features.
The login feature allows existing users to access the platform using their credentials.
Once authenticated, users are redirected to the interface corresponding to their role (Administrator, Manager, or User).
The sign-up feature allows the creation of new user accounts. During registration, the user can choose their role. In the case of a standard user, the account is activated automatically after registration. On the other hand, when a manager creates an account, it is placed on hold pending validation. The final activation of the manager account is subject to prior approval by the administrator, in order to ensure account reliability and limit unauthorized access to management features.
The authentication interface offers smooth navigation between the login and sign-up pages, providing a simple, intuitive, and consistent user experience.
<img width="1916" height="911" alt="image" src="https://github.com/user-attachments/assets/50da4201-af33-49ee-a6be-1418d7552ef2" />
<img width="1918" height="914" alt="image" src="https://github.com/user-attachments/assets/059e28ac-f981-4222-ab92-7f4ce9c0fc21" />
Access to the sign-up page from the login interface via the “Sign Up” button.
Creation of a new user or manager account, with the manager account placed on hold until validated by the administrator before activation.
When registering as a manager, the account is not activated immediately. The user is redirected to a waiting page informing them that their account creation request has been recorded and is pending validation by the administrator. This mechanism aims to strengthen system security, ensure manager account reliability, and limit unauthorized access to sensitive task management features.
<img width="1919" height="915" alt="image" src="https://github.com/user-attachments/assets/9e7a29ce-e5c3-4627-82d3-a9df71315e22" />
4.2 Role Management and Access Control
The system is based on role management that controls access to different features according to the user’s profile. Three main roles are defined: administrator, manager, and user, each with specific permissions tailored to their responsibilities.
The administrator has the broadest rights. They are responsible for the overall management of the system, validating manager account creation requests, managing users, and controlling access to sensitive features. This role ensures the smooth operation of the platform and compliance with security rules.
The manager has access to task-related management features. They can create, edit, and track tasks, view assigned users, validate or reject deliveries, and manage financial aspects related to rewards. Access to these features is strictly limited to manager accounts validated by the administrator.
The user has restricted access to the features necessary for task execution. They can view their assigned tasks, accept missions, deliver work, manage their skills, and view their activity history, without being able to access management or administration functions.
This role and access control management ensures a clear separation of responsibilities, strengthens system security, and prevents unauthorized actions, while guaranteeing a consistent and efficient organization of work within the platform.
4.3 Task Management (CRUD)
The system integrates comprehensive task management based on CRUD operations (Create, Read, Update, Delete), enabling clear and efficient organization of the task lifecycle within the platform.
Task creation (Create) is handled by the manager. During creation, the manager defines essential information such as the title, detailed description, required skills, financial reward (bounty), as well as the start date and deadline. Once created, the task becomes visible and available to users matching the required skills.
<img width="1917" height="909" alt="image" src="https://github.com/user-attachments/assets/0f1d02b0-cc4b-4962-b61f-800837bd0f11" />
Task viewing (Read) allows managers to view all tasks they have published, along with their progress status (pending, in progress, delivered, validated, or rejected). Users, for their part, can view tasks assigned to them as well as their history of previous missions.
<img width="1919" height="910" alt="image" src="https://github.com/user-attachments/assets/a4cdb301-4854-4b0d-9f94-208b8695df55" />
Task updating (Update) allows the manager to modify a task’s information as long as it has not been finalized, including the description, deadline, or reward. The task’s status is also updated automatically based on user actions (acceptance, delivery) and manager decisions (validation or rejection).
<img width="1919" height="904" alt="image" src="https://github.com/user-attachments/assets/254b95a3-dc79-49d3-9eda-4a6eed6aedb3" />
Task deletion (Delete) is only possible by the manager, and only under controlled conditions, in order to prevent data loss or inconsistency in the system. A completed or already assigned task cannot be deleted without appropriate justification.
This CRUD system ensures a structured workflow, from task creation through to final validation, while guaranteeing traceability of actions, data consistency, and efficient work management.
4.4 Skills Management
The system integrates a dedicated module for managing user skills, enabling better alignment between proposed tasks and user profiles. This feature plays an essential role in the organization of work and the efficient assignment of tasks.
<img width="1197" height="288" alt="image" src="https://github.com/user-attachments/assets/05c54c8e-58e3-4c74-bf90-a55a0ee3744d" />
Each user has a personal space allowing them to view their list of registered skills, along with their proficiency level (beginner, intermediate, or expert). This view provides a clear and structured overview of the user’s skills, making it easier to track their professional profile.
<img width="1915" height="736" alt="image" src="https://github.com/user-attachments/assets/19f469bf-5cad-48a8-9dbe-6dc8e3344684" />
The user can add new skills through a simple and intuitive interface. When adding a skill, they select the skill name and their proficiency level. This information is then saved in the system and linked to the user’s profile.
<img width="1914" height="724" alt="image" src="https://github.com/user-attachments/assets/18b23b5f-ae95-482f-9dfd-946f4f6c5b17" />
The system also allows the modification of existing skills. The user can update the level of a skill to reflect the evolution of their knowledge and experience. This flexibility ensures that the user’s profile always remains up to date and faithful to their actual capabilities.
<img width="1919" height="701" alt="image" src="https://github.com/user-attachments/assets/fe3db32d-3393-4521-aa69-d090dd366782" />
Finally, the user can delete a skill that has become obsolete or irrelevant. All these operations (add, view, update, and delete) follow CRUD principles and ensure consistent and secure data management.
Skills management directly contributes to improving the system’s overall workflow by allowing managers to propose tasks suited to user profiles and by fostering a more effective and relevant distribution of work.
4.5 Manager Financial Balance Management
The system integrates a financial management module dedicated to the manager, allowing them to track and control their balance directly from the dashboard. This module provides a clear, real-time view of available funds related to published and validated tasks.
The manager can add funds to their account in order to finance the tasks they wish to propose on the platform. These funds are then used to award financial rewards (bounties) to users after the validation of completed tasks.
<img width="1919" height="905" alt="image" src="https://github.com/user-attachments/assets/d9af4bc0-1fdf-4589-b134-7b304fc9351c" />
In addition, the system allows the manager to perform fund withdrawal operations according to defined rules, ensuring secure and controlled transaction management. All financial operations are automatically updated in the system to ensure data consistency and traceability of actions.
<img width="1915" height="915" alt="image" src="https://github.com/user-attachments/assets/a8761c25-5dcb-4a29-a95e-7c0e4e45730b" />
This feature helps reinforce the financial transparency of the system and offers a professional experience suited to both individual projects and use in an organizational or entrepreneurial environment.
Database Design
What are the key entities of your system?
Provide an ER diagram or a description of the database tables and their relationships.
Identify the models that will represent these entities and their relationships (e.g., hasMany, belongsTo, manyToMany).
The database of the system has been designed according to a relational model to ensure consistent data organization, good referential integrity, and ease of system evolution. It is based on several main entities representing the different actors and features of the platform.
<img width="1060" height="687" alt="image" src="https://github.com/user-attachments/assets/c22ad877-ece2-4c5f-8cc3-afad77a4ca84" />
5.1 Main System Entities
User
The User entity represents users who execute tasks on the platform. It contains the personal information necessary for authentication and user identification, such as identifier, full name, phone number, address, email, and password.
A user can: - own multiple skills, - be associated with multiple tasks over time.
Manager
The Manager entity represents the supervisors responsible for creating and managing tasks. It contains the manager’s personal information, necessary for account management and authentication.
A manager can: - create multiple tasks, - track the progress of published tasks, - manage associated financial rewards.
Administrator (Admin)
The Admin entity represents the system administrator. It enables the overall management of the platform, including validation of manager accounts, supervision of users, and control of access.
5.2 Manager Request Management (Manager_Request)
The Manager_Request entity is used to manage the manager account creation process. When a user wishes to register as a manager, their information is first recorded in this table with a status indicating the state of the request (pending, accepted, or rejected).
This mechanism allows: - strengthening system security, - ensuring manager account reliability, - preventing unauthorized access to sensitive features.
5.3 Task Management (Task)
The Task entity represents tasks published on the platform. Each task contains detailed information such as: - the title, - the description, - the status, - the financial reward (bounty), - the start and end dates, - the required skill.
Each task is: - created by a single manager, - assigned to a single user at a time.
The foreign keys user_id and manager_id ensure the link between tasks, users, and managers.
5.4 Skills Management (Skills)
The Skills entity manages user skills. Each skill is associated with a user and includes: - the skill name, - the proficiency level (beginner, intermediate, or expert).
This structure allows: - better alignment between proposed tasks and user profiles, - more relevant task assignment, - clear tracking of skill development.
5.5 Relationships Between Entities
The relationships between entities are defined according to the following rules: - a user can have multiple skills (1,n relationship), - a manager can create multiple tasks (1,n relationship), - a task is associated with a single user and a single manager (1,1 relationship), - each manager request is linked to an administrator validation process.
These relationships ensure data consistency and provide a structured and reliable workflow within the system.
Models and Migrations (if using Laravel)
Models: describe each model in your system and how it corresponds to your database tables. Indicate the relationships between models (e.g., users and posts).
Migrations: specify the migrations needed to create the database tables and all constraints (e.g., foreign keys, nullable fields).
Front-End Presentation
What technologies will you use for the front-end (e.g., HTML, CSS, JavaScript, frameworks)?
How will users interact with your application? Include wireframes or descriptions of key views (e.g., user dashboard, resource creation or editing forms).
The front-end of the application has been designed to offer a clear, intuitive, and easy-to-use interface for the different types of users in the system. The main objective is to ensure a smooth user experience while facilitating access to the platform’s essential features.
The user interface has been developed using standard web technologies: HTML for page structure, CSS for styling and design, and JavaScript to ensure interactivity and improve the user experience. These technologies were chosen for their compatibility with modern browsers and their ability to create responsive interfaces suited to different devices.
The system offers several distinct interfaces depending on the user’s role. The authentication page allows users to log in or create an account through smooth navigation between the login and sign-up pages. Particular attention was paid to the simplicity of this interface in order to facilitate access to the system from the very first use.
After authentication, each user is redirected to a dashboard corresponding to their role. The manager has a dashboard allowing them to manage tasks, track their progress, view assigned users, and manage their financial balance. The user, for their part, accesses an interface allowing them to view their tasks, manage their skills, and track their activity history. The administrator benefits from a dedicated interface for managing accounts and validating manager registration requests.
Interaction with the system is done primarily through simple forms and clear tables, enabling the addition, modification, and deletion of data in an intuitive way. Important actions are accompanied by visual confirmations to avoid errors and improve understanding of the operations performed.
Finally, the front-end has been designed in a modular and scalable way, making it easy to add new features or improve the existing interface without impacting the overall functioning of the system.
Back-End Presentation
Describe the core logic of your application.
How will the back-end handle user interactions and requests?
Discuss your middleware strategy for tasks such as authentication, logging, and other cross-cutting concerns.
The back-end of the application constitutes the logical core of the system. It is responsible for data processing, business rule management, communication with the database, and securing the operations performed by users.
The system was developed using the PHP language for the server side, based on a clear architecture separating business logic, data access, and interaction with the user interface. This organization ensures structured, maintainable, and scalable code.
The back-end manages authentication and user sessions. It verifies credentials during login, controls roles (administrator, manager, user), and restricts access to features based on defined permissions. Passwords are stored securely using hashing mechanisms to protect sensitive data.
Communication with the MySQL database is carried out using secure queries, enabling the creation, reading, updating, and deletion of data (CRUD). The back-end manages the various system entities such as users, managers, tasks, skills, and financial operations, while ensuring data integrity and consistency.
The system also implements business logic specific to the project, including the validation of manager accounts by the administrator, the assignment of tasks to users, the management of task statuses, and the processing of financial operations related to rewards and manager balances.
Finally, the back-end has been designed in a modular way to facilitate the addition of new features and the future evolution of the system, while ensuring good performance and smooth interaction with the application’s front-end.
Form Management and Validation
Describe the forms in your application (e.g., user registration, product creation).
How will you handle form validation (e.g., request validation, custom validation rules)?
The application relies on several forms that allow users to interact with the system and perform the different operations offered by the platform. These forms are an essential element of the application’s functioning and data management.
Among the main forms in the system are: - the user registration and login form, - the task creation, modification, and delivery form, - the skills management form (add, modify, and delete), - forms related to the manager’s financial balance management (adding and withdrawing funds), - the manager account creation request form subject to validation.
Form validation is primarily handled server-side using PHP, in order to ensure the reliability and security of processed data. When a form is submitted, the system systematically verifies the entered data before any storage in the database.
The checks performed include: - verification of required fields, - control of data formats (email address, passwords, numerical values), - validation of business constraints (e.g., consistent dates for tasks, valid amounts for financial operations), - prevention of invalid or inconsistent data.
In the event of a validation error, explicit messages are displayed to the user to inform them of the corrections to be made. This approach improves the user experience while ensuring the integrity of data stored in the database.
Form management and validation thus contribute to strengthening the overall security of the system, limiting input errors, and ensuring reliable and consistent operation of the application.
Roles and Permissions
Will your project involve different user roles (e.g., administrator, user, guest)?
Describe the roles and the access each role will have.
How will you implement role-based authorization using middleware, Laravel’s authorization policies, or another method?
The system relies on clear role and permission management to ensure a structured organization of work and limit access to features based on user type. Each role has specific privileges corresponding to its responsibilities within the platform.
Administrator (Admin)
The administrator has the highest permissions in the system. They are responsible for the overall management of the platform and can: - validate or reject manager account creation requests, - add, modify, or delete user and manager accounts, - supervise the entire system and control access, - ensure the security and smooth overall functioning of the application.
Manager
The manager has permissions allowing them to manage tasks and track their progress. They can: - create, modify, and delete tasks, - define required skills, financial reward, and completion deadline, - view users assigned to their tasks, - validate or reject tasks delivered by users, - access statistics related to their tasks and manage their financial balance, - modify their personal settings.
User
The user has permissions limited to executing their assigned tasks. They can: - browse available tasks matching their skills, - accept a task and commit to completing it, - deliver completed tasks, - manage their skills, - view their activity and reward history, - modify their personal settings.
Permissions are implemented server-side using role-based controls stored in the user session. For each sensitive action, the system automatically verifies the user’s role to ensure they have the necessary rights to access the requested feature. This approach ensures data security and a clear separation of responsibilities between the different actors in the system.
Web API (if applicable)
Will your project include a REST API or interact with external APIs?
Provide a general overview of your API routes (e.g., GET /posts, POST /users) and the data they will handle.
Describe how your API will handle authentication (e.g., using Laravel Sanctum or Passport).
The project does not rely on an independent REST-type Web API. The system has been designed as a classic web application, in which interactions between the user interface and the back-end are handled directly through PHP pages, HTML forms, and sessions.
Operations such as user authentication, task management, skills, and financial balance are handled server-side without exposing public API routes. Data is exchanged securely between the front-end and the database using internal server requests.
However, the system architecture remains scalable. It would be possible, in a future version of the project, to implement a REST API to enable interaction with external applications, such as a mobile application or a separate front-end. This extension would provide dedicated endpoints for managing users, tasks, and associated data.
In the context of the current project, the absence of a dedicated Web API allows maintaining a simple, consistent architecture adapted to the defined functional objectives.
Third-Party Integrations (if applicable)
Are you using any third-party services or APIs (e.g., payment gateways, social authentication)? Describe how you plan to integrate them into your project.
In the context of the current project, no integration with external third-party services or APIs has been implemented. The system has been designed as a standalone web application, relying solely on internal technologies such as PHP, MySQL, and standard web interfaces.
Financial management features, including the addition and withdrawal of funds for managers, are simulated and handled internally by the system, without the use of external payment gateways. This approach allows maintaining a simple architecture adapted to the educational objectives of the project.
However, the system has been designed in a scalable manner. In a future version, it would be possible to integrate third-party services such as online payment gateways or external authentication systems, in order to enrich the features and improve the user experience.
Challenges
What are the potential technical challenges you anticipate in building this project? For example, managing user roles, optimizing database queries, handling complex validations.
The realization of this project presents several potential technical challenges that require rigorous design and good system organization.
One of the main challenges concerns role and permission management. The system must ensure that each user only accesses the features corresponding to their role (administrator, manager, or user), while preventing any unauthorized access.
Another important challenge lies in the design and optimization of the database. It is essential to ensure the consistency of relationships between the different entities, optimize queries to improve performance, and avoid data redundancy.
Form validation management also constitutes a challenge, particularly in ensuring the integrity of entered data, managing complex business constraints, and providing clear error messages to users.
Finally, system security represents a major concern, particularly regarding session management, secure password storage, and protection against malicious actions or handling errors.
Timeline
Provide an approximate timeline for the project milestones. Include specific phases such as: - Database setup and migrations - Back-end logic implementation - Middleware configuration - Front-end design and API integration - Testing and final deployment
The development of the project was organized according to an approximate timeline divided into several successive phases:
Requirements analysis and general system design: definition of main features and user roles.
Database design: creation of the relational model and ER diagram.
Back-end logic implementation: development of main features in PHP, session management, and business rules.
Front-end design and development: creation of user interfaces and integration with the back-end.
Validation and security management: implementation of input controls and security mechanisms.
Testing and corrections: verification of correct system functioning, bug fixes, and performance improvements.
Finalization and preparation of the final version: code cleanup and preparation of the project for submission.
This schedule allowed for a structured progression of the project and a balanced distribution of the different development tasks.
Conclusion
This project enabled the development of a web-based task management application offering a clear and structured solution for organizing work between managers and users. The system integrates essential features such as authentication, role management, task creation and tracking, as well as skills management and manager financial balance.
The database design and the separation between the front-end and the back-end ensured the consistency, security, and reliability of the system. Despite certain technical challenges, the project resulted in a functional application meeting the defined objectives.
Finally, this application constitutes a scalable foundation that can be improved in the future through the addition of new features and the integration of external services.
