# Personnel PHP Task

## Technologies used

* [HTML5](https://developer.mozilla.org/en-US/docs/Glossary/HTML5) 
* [CSS#](https://developer.mozilla.org/en-US/docs/Web/CSS)
* [Bootstrap 4.6](https://getbootstrap.com/docs/4.6/getting-started/introduction/)
* [PHP](https://www.php.net/)
* [MySQL](https://dev.mysql.com/doc/)

## Installation

You can either download or clone the project.

`git clone https://github.com/IgorTasevski/personnel.git`

`cd personnel`

#### Database

* You will find the export to the database in the folder **database**. After importing it locally, you can start the application

* There are three tables `users`, `clients` and `calls`.

* The structure of the `ussers` table is as follows:

id | name | 
| :---: | :---: 
1  | Tony Stark |
2  | Richard Feynman |

* The structure of the `clients` table is as follows:

id | name | type |
| :---: | :---: | :---:
1  | Name Surname | Carer |  
2  | Name Surname | Nurse | 

* The structure of the `calls` table is as follows:

id | user_id | client_id | date | duration | type | score
| :---: | :---: | :---: | :---: | :---: | :---: | :---: |
1  | FK_users.id | FK_clients.id | dd-mm-YYYY | 1337 | Incoming | 50
2  | FK_users.id | FK_clients.id | dd-mm-YYYY | 1337 | Outgoing | 33

### Setup

* To start this project fill in the consts which can be found in the path: **config/consts.php**
    * The **APP_URL** should reflect the full path to the project files with a `/` at the end
