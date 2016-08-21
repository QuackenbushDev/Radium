# Radium

A full featured radius management system for FreeRadius setups using the mysql backend.

# Features
* User Management
* NAS Management
* Group Management
* Attribute Management
* Reporting
* Accounting reporting

# Screen shots
![Dashboard](http://i.imgur.com/tqNQ5Cx.png)

# Requirements
* Composer
* PHP 5.3+
* NPM / Gulp (css compilation)

# Installation
1. Clone Radium into your web director, cd into Radium, git checkout release
2. Create a vhost for the domain that points to the public folder
3. Copy .env.example to .env and configure the application settings
4. Install Composer and run composer install
5. Run php artisan key:generate to generate a new application secret for Radium
6. Run php artisan db:seed --class=OperatorSeeder
