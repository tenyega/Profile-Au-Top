CREATING A Symfony project 
	Symfony new project_symfony --webapp

INSTALLING TAILWIND
	composer require symfonycasts/tailwind-bundle
	php bin/console tailwind:init
	php bin/console tailwind:build --watch


ENTITY
	symfony console make:User
		-id int not null unique
		-email varchar120 not null 
		-password varchar120 not null
		-firstName varchar120 not  null
		-lastName varchar120 not null
		-createdAt datetimeimmutable not null
		-updatedAt datetimeimmutable not null
		-image varchar255 nullable 
		-roles array not null


	symfony console make:entity JobOffer
		-id int not null unique 
		-title varchar180 not null
		-company varchar180 not null
		-link varchar120 nullable 
		-location varchar255 nullable
		-salary varchar180 nullable
		-contactPerson varchar120 nullable 
		-contactEmail varchar120 nullable 
		-applicationDate date not null 
		-status ennum('A postuler, En attente, Entretien, Refusé, Accepté) not null
		-app_user M21 with User not null orphan removal yes 


	symfony console make:entity LinkedInMessage
		-id int not null unique 
		-content text not null 
		-createdAt datetimeimmutable not null 
		-updatedAt datetimeimmutable not null 
		-jobOffer M21 with JobOffer not null orphan removal yes
		-app_user M21 with User not null orphan removal yes


	symfony console make:entity CoverLetter
		-id int not null unique 
		-content text not null
		-createdAt datetimeimmutable not null 
		-updatedAt datetimeimmutable not null 
		-jobOffer M21 with JobOffer not null  orphan removal yes
		-app_user M21 with User not null orphan removal yes




CONTROLLER
	symfony console make:controller Home
	symfony console make:controller JobOffer
	symfony console make:controller LinkedInMessage
	symfony console make:controller CoverLetter



FIXTURES 
	composer require orm-fixtures --dev
	composer require fakerphp/faker



CREATION BDD
	create.env.local and change the necessary BDD
	symfony console d:d:c
	symfony console make:migration
	symfony console d:m:m  


LOADED THE FIXTURES 
	symfony console d:f:l


CREATING LOGIN FORM AND REGISTRATION FORM
	symfony console make:security:form-login 
		SecurityController with /logout and no phpUnit test
	symfony console make:registration-form
		 composer require symfonycasts/verify-email-bundle 

	 symfony console make:entity User
		added is_verified

INSTALLED UX ICON FROM SYMFONY 
composer require symfony/asset-mapper symfony/stimulus-bundle

ADMIN
	composer req easycorp/easyadmin-bundle
	symfony console make:admin:dashboard	
	symfony console make:admin:crud 


PAGINATION (knp paginator bundle) 
	composer require knplabs/knp-paginator-bundle
	
	copy from vendor\knplabs\knp-pagination-bundle\templates\pagination\tailwindcss to template\components\pagination.html.twig
	created paginator.yaml with contents already given and change    << pagination: "components/pagination.html.twig">>



ADMIN
	 composer req easycorp/easyadmin-bundle
	symfony console make:admin:dashboard