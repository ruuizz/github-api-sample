# Popular PHP Repositories on GitHub
===================================

• This project provides a simple list of the top 1K PHP repositories hosted in Github. The repositories are ranked by the number of stargazers.

• This project connects to Github Search API & stores the max number of repos allowed for public clients (1k entries) in a local database. Data pull can be done via cli or UI.

• At this time Javascript is required for a full functional site, a warning message will display if the user has javascript turned off.

• Project is built using Symfony 3.4 

• Project uses Bootstrap CDNs for the presentation layer, so internet connection is required.

• WebPack was left out purposely, to reduce number of dependencies needd to be installed to run this project.

• Simple unit & functional tests have been included to this project. At the time of development I need deeper understanding of tests for private services. 

• I can also work out some Mink tests and add in the future.


From the list of requirements document

- [x] Use the GitHub API to retrieve the most starred public PHP projects.
- [x] Store the list of repositories in a MySQL table.
- [x] The table must contain the repository ID, name, URL, created date, last push date, description, and number of stars. 
- [x] This process should be able to be run repeatedly and update the table each time.
- [x] Using the data in the table created in step 1, create an interface that displays a list of the GitHub repositories and allows the user to click through to view details on each one. 
- [x]Be sure to include all of the fields in step 1 – displayed in either the list or detailed view.
- [x]Create a README file with a description of your architecture and notes on installation of your application.


## Requirements

• php >=7.1
• mysql
• composer


## Before Installation 


1. Create a mysql database.
2. Install composer to manage php packages/libraries needed to run this app.


## Installation


1. Download or clone repository
2. cd into app root directory 
3. Run 

```
composer install
```

4. Fill parameter information directly from command line or make copy parameter.yml.dist in the same location and name it parameters.yml. Fill out parameter information for DB

5. You can view the schema on your database needed by running:


```
bin/console doctrine:schema:update --dump-sql
```

6. You can create the schema on your database needed by running:

```
bin/console doctrine:schema:update --force
```

7. Pull the data by running

```
bin/console github-repo:store-search-results php
```
** php is the argument passed to the search.


8. Start the server

```
bin/console server:run
```


9. Go to http://localhost:8000/ in your browser to use the app.



Tests

For testing, make sure phpunit.xml file is pointing to use a version higher than 6.
For that add the environment variable value to the php block.
Ex.

```
    <php>
        <ini name="error_reporting" value="-1" />
        <server name="KERNEL_CLASS" value="AppKernel" />
        <env name="SYMFONY_PHPUNIT_VERSION" value="6.5" />
    </php>
```

Also you will need to add the PhpUnitlistener block after filter block

```
    <listeners>
        <listener class="\DAMA\DoctrineTestBundle\PHPUnit\PHPUnitListener" />
    </listeners>
```


From your app root directory run

```
    vendor/bin/simple-phpunit --verbose
```