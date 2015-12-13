# rest-example
The purpose for this project is to cover some example of using Zend Framework 2 to build a REST API for managing Employees and their Work Shifts.  This project is meant for demonstration purposes only and is not a complete application API to be use.

## Requirements
* CentOS 6
* PHP 5.3.3 or newer

## Deploying
* Install and configure Apache/MySQL
    yum install -y httpd mysql mysql-server
    service httpd start && service mysqld start
    chkconfig httpd on && chkconfig mysqld on
    mysql -e "CREATE DATABASE rest_example"
    mysql -e "CREATE USER 'user'@'localhost' IDENTIFIED BY 'pass'"
    mysql -e "GRANT ALL ON rest_example.* TO 'user'@'localhost'"

* Download and install `rest-example` at `/var/www/vhosts/rest-example`

* Execute Composer Install
    cd /var/www/vhosts/rest-example
    php composer.phar install
	
* Configure Virtual Host
    ln -s /var/www/vhosts/rest-example/info/rest-example.httpd.conf /etc/httpd/conf.d/rest-example.conf
    service httpd restart
	
## Features and usages
* As an employee, I want to know when I am working, by being able to see all of the shifts assigned to me.
    curl http://localhost:8080/rest/users/1/shifts/

* As an employee, I want to know who I am working with, by being able to see the employees that are working during the same time period as me.
    curl http://localhost:8080/rest/users/1/shift-members/
	
* As an employee, I want to know how much I worked, by being able to get a summary of hours worked for each week.
    curl http://localhost:8080/rest/users/1/weekly-hours/
	
* As an employee, I want to be able to contact my managers, by seeing manager contact information for my shifts.
    curl http://localhost:8080/rest/users/1/shift-managers/
	
* As a manager, I want to schedule my employees, by creating shifts for any employee.
    curl --data "employee_id=1&start_time=Mon, 14 Dec 2015 08:00:00 -0800&end_time=Mon, 14 Dec 2015 17:00:00 -0800" http://localhost:8080/rest/managers/2/shifts/
	
* As a manager, I want to see the schedule, by listing shifts within a specific time period.
    curl http://localhost:8080/rest/managers/1/shifts/?start_time=Mon%2C+14+Dec+2015+08%3A00%3A00+-0800&end_time=Mon%2C+14+Dec+2015+17%3A00%3A00+-0800
	
* As a manager, I want to be able to change a shift, by updating the time details.
    curl -X PUT --data "start_time=Mon, 14 Dec 2015 08:00:00 -0800&end_time=Mon, 14 Dec 2015 17:00:00 -0800&break=2" http://localhost:8080/rest/managers/2/shifts/6
	
* As a manager, I want to be able to assign a shift, by changing the employee that will work a shift.
    curl -X PUT --data "employee_id=3" http://localhost:8080/rest/managers/2/shifts/6
	
* As a manager, I want to contact an employee, by seeing employee details.
    curl http://localhost:8080/rest/managers/2/employees/