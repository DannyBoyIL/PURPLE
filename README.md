# PURPLE

## General

A mini API project composed in Laravel framework 8.0. 

### Instellation and Setup:

-   Download project from GitHub repository [link](https://github.com/DannyBoyIL/PURPLE).
-   Extract project  **_PURPLE_**  And place it inside you local server directory  `C:\xampp\htdocs`  or any other directory defined and running my  **xampp**  local server.
-   Open local  **xampp**  server and make sure that  _pdo_mysql_  extension isn't commented out on  **PHP.ini**  file and ready for use.
-   Run  _Apache_  local server and  _MySQL_  local database.

### Database Setup:
1.  Open the browser and navigate on the url to your  `localhost<:port>\phpmyadmin\`  that's currently running, for example:  `localhost:8000\phpmyadmin\`  |  `localhost:80\phpmyadmin\`  |  `localhost\phpmyadmin\`.
2.  Open new database under the name  **`purple`**  set collation to  `utf8mb4_general_ci`  and make sure engine is set to `InnoDB`.
3.  After successfully Defining the new database, open your Command Prompt tool, navigate to the project local repository, E.g `C:\xampp\htdocs\PURPLE>`.

#### run the following script to create the project’s table: 
	composer require laravel\framework
	php artisan migrate
	php artisan db:seed

## Usage:

Get dedicated Postman collection from [link](https://www.getpostman.com/collections/4db60d44b10771332af9).

Open your Postman development tool make use of the collection retrieved. Make sure to verify that the requests base url are set up to your current local environment **`<http://localhost:8000>`**`/PURPLE/public/api/users` .

For each request in the collection there is a description added to help you through the system checks. With that said, a summary  of the entire collection is brought to you here below for you comfort:

## users  folder

> GET - All Users: 	

_On send request return all users._

> GET - Read User

_On send request return single user._

_Change the_ **`<id>`** _variable of the last URL segment to switch between users._ _'.../api/users/_**`<id>`**_'_
E.g `/api/users/1`
E.g `/api/users/2`
E.g `/api/users/7`

> POST - Update User

On send request updates users' profile image/name.

_Change the_ **`<id>`** _variable of the last URL segment to switch between users._ _'.../api/users/_**`<id>`**_'_
E.g `/api/users/1`
E.g `/api/users/2`
E.g `/api/users/7`

_Fill in the request body and select the_ `form-data` _option._ 
_Add and/or edit the image and/or name fields in the designated area under the_ `value` _column._

_Do not change the_ `key` _column in order to avoid malfunction._

_No additional headers are required._

> POST - Create User Items

On send request create items list for a single user only once.

_Change the_ **`<id>`** _variable of the URL segment prior to the last in order to switch between users. '.../api/users/_**`<id>`**_/items'_
E.g `/api/users/1/items`
E.g `/api/users/2/items`
E.g `/api/users/7/items`

_No additional headers are required._

## trades folder

> GET - All Trades

_On send request return all trades._

> GET - All Deals by User

_On send request get bids history list for a single user. return current user data, the trader data (the new_ **`<owner>`**_), the previous bid data, and the traded data._ 

_Change the_ **`<id>`** _variable of the URL segment prior to the last in order to switch between users. '.../api/users/_**`<id>`**_/deals'_
E.g `/api/users/1/deals`
E.g `/api/users/2/deals`
E.g `/api/users/7/deals`

_No additional headers are required._

> POST - Create User Bid

_On send request create a bid available for trading. return the newly created bid._

_Change the_ **`<id>`** _variable of the URL segment prior to the last in order to switch between users. '.../api/users/_**`<id>`**_/bid'_
E.g `/api/users/1/bid`
E.g `/api/users/2/bid`
E.g `/api/users/7/bid`

_Fill in the request body and select the_ `form-data` _option._ 
_Add and/or edit the json object in the designated area under the_ `value` _column with_ **`<key> <value>`** _pairs._ **`'{"<key>": "<value>"}'`**

_Make sure to write the item you wish to bid for as the_ **`<key>`** _avoiding typos._
_Make sure to indicate the bid amount (not value) as the_ **`<value>`** _value._
E.g `'{"chicken": 1, "water": 2}'`
E.g `'{"glass": 3}'`
E.g `'{"house": 1}'`

_Do not change the_ `key` _column in order to avoid malfunction._

_No additional headers are required._

> POST - Create User Trade

_On send request create a trade for a available bid. return the newly created trade._

_Change the_ **`<id>`** _variable of the URL segment prior to the last in order to switch between users._ `'.../api/users/`**`<id>`**`/trade'`
E.g `/api/users/1/trade`
E.g `/api/users/2/trade`
E.g `/api/users/7/trade`

_Fill in the request body and select the_ `form-data` _option.
Add and/or edit the bid_id field that you wish to trade for in the designated area under the_ `value` _column.
Add and/or edit the json object in the designated area under the_ `value` _column with_ **`<key> <value>`** _pairs._ **`'{"<key>": "<value>"}'`**

_Make sure to write the item you wish to bid for as the_ **`<key>`** _avoiding typos.
Make sure to indicate the bid amount (not value) as the_ **`<value>`** _value._
E.g `'{"chicken": 1, "water": 2}'`
E.g `'{"glass": 3}'`
E.g `'{"house": 1}'`

_Do not change the_ `key` _column in order to avoid malfunction._

_No additional headers are required._
