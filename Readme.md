## devSave - Bookmarking application

### Why ? 

I had to do a project for university and decided to make it a little more close to real life.

The backend will be done as a RESTful api in Laravel 4.1, however I will try to abstract the application logic as much as it is reasonable for the size of the application.

Since I've been learning AngularJS recently this will be a good time to try it out in such an application. 

Also I will try to cover as much of the backend and frontend as possible with tests.

In the meantime ( probably short ) this will be updated with links to other documents describing the process of creating the application.

The vagrant setup is based on [Jeffrey Way's](https://github.com/JeffreyWay/Vagrant-Setup) with a few additions by myself.

To get it up and running you need vagrant installed on your system and just run:

``` vagrant up ```

This will install all the necessary packages on a Ubuntu 12.04 box, setup mysql with **root/root** credentials, create a database for the project called **devsave** and run the database migrations and seeds. 

*Note: I'll be using the root user, because this is a dev environment only.* 

## Contributions 

I would like to keep this as an example of a simple application for anyone willing to check it out, so anything that can make it better is most welcome, and since I am learning as well it will be very helpfull to me as well. 

## Licence 

This will be kept open source and available for anyone willing to check it out. 