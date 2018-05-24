# Online Proofing

![](.github/head.png?raw=true)


* [Introduction](#introduction)
	* [Requirements](#requirements)
	* [Services](#services)
* [Installation](#installation)
	
## Introduction
This is an online proofing system.


### Requirements
* PHP 7.1 or greater
* MYSQL 5.7.19 or greater
* Imagick + ImageMagick
* Ghostscript
* NPM
* Composer
* GD Library

### Services Needed
* Dropbox
* SMTP Email



## Installation
These are the rough installation instructions as of now.  Eventually I want to wrote some sort of auto insaller.

1. Ensure you have all the requirements and dependencies installed
1. Clone project from git
1. Navigate to project root folder
1. Copy `.env.example` into an `.env` file.  Adjust values as needed.  Ensure you properly fill out database and smtp connection info
1. Run `composer install` to install all packages
1. Run `npm install` to install all js packages
1. Run `php artisan vendor:publish` to generate all vendor package files needed
1. Run `php artisan migrate` to create databases
1. Run `php artisan db:seed` to bring in initial admin.
1. Run `php artisan queue:listen` to start job listener.  If deploying use some sort of worker

## Screenshots

![](.github/head.png?raw=true)