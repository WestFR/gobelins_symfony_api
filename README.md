# eSchool API

> ESchool is a platform on which the professors are going to be able to estimate the children of their class on their behavior. 

## Description

This platform allow a permanent follow-up for the parents who can connect to the application and visualize the advance of his / his child.

A teacher can assure several classes (example : 1st year of primary school and 2nd year of primary school). <br>
Child's list is associated with every class, and every child is connected with a parent. <br>
All year round the teacher distributes negative or positive actions to a child. He will have the choice to choose actions predefined or what he will have create himself.

Every action is connected with a score going from -5 (if it is negative) to 5 (if it is positive). 
And, when this level changes, the parent is warned by e-mail thanks to a symfony service which we developed.

In every new school year, the classes of the teachers will be reset and redefined with his new pupils.

Note : We made as asked for an application customer who consumes 5 roads of the API to which we applied a simple and uncluttered design, but adds us to us mainly concentrated on the development of the API to be able to work and learn Symfony.

Follow this instructions to install the application & develop your features !


## Requirements

- Install Composer (Globally is better) : https://getcomposer.org/
- A local server environment such as MAMP / XAMPP / LAMP configured with PHP 5.5.9 or higher (7+ is better).

## Installation

1. Clone this project in your project folder : <br>
`git clone https://github.com/alberluc/gobelins_symfony_api.git`

2. After have installed Composer, do this command line in your project's folder : <br>
`composer install`

3. Launch your server environment and your create your database with this command line : <br>
`bin/console doctrine:database:create`

4. Now, make migrations for create tables in your database : <br>
`bin/console doctrine:migrations:migrate`

5. After, loads fixtures for get datas in your differents class of your database : <br>
`bin/console doctrine:fixtures:load`


## Usage

1. Launch your server environment and execute this command in your project's folder : <br>
`bin/console s:r`

2. You can use API endpoints directly from documentation or via different routes (the default passwords of users in fixtures are 0000)


## API

A simple API is available on this application.

- A documentation is available : `http://localhost/`
- You can use this API directly with this documentation or thanks an external application such as : https://github.com/alberluc/gobelins_symfony_app


## What's else ?

Enjoy (and put a star if you like this app) !
