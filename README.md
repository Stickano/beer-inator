# Beer Inator
This was my contribution to the 3rd semester exam in *Design*. This whole code base was actually not that important as it was much more a demonstration of developing in an Agile Environment - mainly Scrum in this case. We touched upon Extreme Programming, DevOps and Scrum methods throughout this project, which concluded in a 30 pages report documenting each sprint (iteration) and how we ended up with our final product through User Stories, Kanban Boards, Burndown Charts and various Inception Deck exercises. 

As an Agile Development team, our task on hand was to create a product consisting of a sensor, connected to a Raspberry Pi, that would broadcast, via UDP, the current amount of beers in the Friday-bars fridge. This data would then be picked up on the network and sent to a Rest interface, which would store it in an Azure database. We had quite a few accept criteria, a.a. that we needed to build a nice visual animation for the bar, to display the amount of beer in the fridge, and a CMS for administrating various data - Like notification values, profiles and statistics. 

This was a 5 man project. The combined repository can be found [here](https://github.com/Banoga/Beer-inator). This particular code base was my contribution for the project in whole. This is two separate web interfaces - Beer Animation, for the bar, and CMS for administration - which is build on a homemade MVC patter in PHP. These two web-project bases it's features and values provided by a Restful interface which is also included.

&nbsp;

#### Content Management System
The CMS has a profile ranking - Administrator and Purchase Manager. The administrator will have access to the whole CMS while the purchase manager is limited to the __Buyers__ page. The difference is, that an admin can set notification values - minimum values for the fridge and stock - and add additional profiles, where a purchase manager can only update the stock value (When a new purchase has been done) and see statistics for the sales. The __Buyer__ page also collects nearby beer offers (web-scraping). 

I never did get around to finish the statistics page, but besides that everything should be in somewhat working order. This project only ran over a 4-week period, whereas 3 of the was for programming. So in collective our project could very easy run out of hand with features and tasks that there just wasn't time for - Keep in mind that this was a Design study, so the focus was much more on proving the agile methods. 

<p align="center"><img src="https://github.com/Stickano/beer-inator/blob/master/cms_new1.png" width="50%">
<img src="https://github.com/Stickano/beer-inator/blob/master/cms_new_4.png" width="50%"></p>

&nbsp;

#### Beer Animation
For the bar we needed a nice little indicator for the amount of beer in the fridge. We had a couple of version and this was kind of my last minute attempt for a minimalistic design, which was something we had talked about throughout. I'm not much of a designer, but I think it turned out okay. The page will auto-refresh every couple of seconds and display the percentage and actual value of beer in the fridge. 

<p align="center"><img src="https://github.com/Stickano/beer-inator/blob/master/beer_1.png" width="50%">
<img src="https://github.com/Stickano/beer-inator/blob/master/beer_2.png" width="50%"></p>

&nbsp;

#### Restful API (Interface) and Azure SQL Database
In all 17 accessible methods through HTTP requests. In the short amount of time that we had, I did not take to many spikes looking into security and best practices, but I did try and use a bit of common sense and a few various security steps. Of course there is a login to the CMS and profiles has roles determining which pages the user can access. There's also a random pepper string which is required when making request we don't want accessible for everyone with the knowledge of making a HTTP request to our Rest interface. This includes viewing and adding profiles, adding new beer and notification values and so forth. The Restful interface then communicate whatever request with data stored in a Azure database. 

&nbsp;

##### Have a look
Feel free to try the live example: [CMS](https://sloa.dk/beerInator/admin) and [Bar](https://sloa.dk/beerInator/bar).

You can log in to the CMS with `mail@mail.mail` and the password is `hejhej`. I've disabled the feature to add and delete new profiles so the amount of havoc you can create in the CMS is minimal, though you're a nice person so you wouldn't be thinking that anyways. Right? Guys..?

&nbsp;

Anyways, this product was not chosen by my team and such it was only briefly presented. Quite a few sleepless nights were spent, though, so this is my shameful last attempt of showing my beloved code to the world. All code is loved code.. Well, until you look back upon that is. 
