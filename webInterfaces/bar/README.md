# Web Interface for Beer-Inator
A small documentation from a developer/contributer point-of-view.

&nbsp;

* [3rd Parts](#3rd-parts)
* [MVC Design](#mvc-design)
* [Displaying Errors](#displaying-errors)
* [User Profiles](#user-profiles)
* [Database and Crud](#database-and-crud)
* [Crud Examples](#crud-examples)
  * [Create](#create)
  * [Read](#read)
    * [Join](#join-clauses)
    * [Results](#results)
  * [Update](#update)
  * [Delete](#delete)
* [Helper Classes](#helper-classes)
  * [Singleton](#singleton)
  * [Validators](#validators)

&nbsp;

### 3rd Parts
This interface uses a few 3rd part resources. More will probably be added along the way.

[JQuery](https://jquery.com/)

[Skeleton Boilerplate](http://getskeleton.com/)

[Font Awesome](http://fontawesome.io/)

&nbsp;

### MVC Design
Visual pages, as displayed for the user, is stored in the `views` folder. 

A view needs a Controller, with the same document name as the view document, in the `controllers` folder.

When you anchor (`<a href="">`), that href tag needs to point to an URL containing the same value as the document name for the view and controller. 

So, f.ex., if you create a view called `page2.php` in the `views` folder you also need to create a `controllers/page2.php`. To then link for that page you would write your anchor like `<a href="?page2">Page 2</a>` - This way the design will automatically determine and load the appropriate view and its controller.

&nbsp;

### Displaying Errors
The singleton file will perform a check to see if any sessions with the name `error` is set and display it accordingly if true. You can use the `SessionHandler`-class to create a new error session - It will be handled (unset ex) accordingly. The `SessionHandler` is loaded once in the `Singleton`.

```
$session = $this->session;
if ($somethingIsNotRight)
    $session->set('error', 'Something went wrong');
```

&nbsp;

### User Profiles
Profiles consists of: `Email`, `Password` (Hashed), `Full Name` and `Role`.

Role is an integer value: `1 = Purchase Manager` and `2 = Administrator`. 

When no profile is found in the database (no profile has been created yet), the site will offer to create an administrative profile for you. From this first administrative profile you will then be able to perform the settings to get a fully functioning site. 

There's a `profile`-class which has a method for creating the profile to the database. Pass along the `connection` and `crud` classes to the method.

```
$mail = 'Some@Email.com';
$fullname = 'John Doe';
$pwHash = password_hash('StrongPassword', PASSWORD_DEFAULT);
$role = 2;

$profile = new Profile($mail, $fullname, $pwHash, $role);
$profile->insertToDb($conn, $db);
```

&nbsp;

### Database and CRUD
There's an `.sql` file included for the database design. 

To point this interface to you database, fill in your credentials in the `resources/credentials.php` document. 

This credential document will be read once by the singleton and the singleton will then construct a connection class which can then be used to construct a new CRUD class. 

When the singleton constructs a controller it will pass along a Connection, Crud and SessionHandler class to that controller, so their individual features will be easily accessible in that controller document.

&nbsp;

### CRUD Examples
The Create/Read/Update/Delete class will secure inputs, along with building and running the SQL query. The CRUD methods depends a lot on arrays, which can then be looped over to build advanced query strings. 

```
$db = new Crud($conn);
```

##### Create
Takes 2 parameters, both are required!; `string $table` and `array $data`.

`$table` is a string value with the name of the table to create this new value in.

`$data` is an array of `'row' => 'value'`, where `row` is the table-row to affect and `value` is the value of that table-row.

```
$table = 'profiles';
$password = password_hash('strongPassword', PASSWORD_DEFAULT);
$data = ['umail' => 'John@doe.com', 'upass' => $password, 'fullName' => 'John Doe', 'role' => 2];

$db->create($table, $data);
```

##### Read
Takes 5 paramters, only 1 is required!; `array $select`, `array $where`, `array $order`, `int $limit`, `array $join`. This will return a multi-dimensional array with the results, meaning that you have an array with arrays with values.

`$select` is an array of 'SELECT [data] FROM [table]': `'data' => 'table'`. This data is *required*.

`$where` is an array of 'WHERE [row] = [value]': `'row' => 'value'`.

`$order` is an array of 'ORDER BY [row] [asc/desc]': `'row' => 'desc'`.

`$limit` is an integer of 1 value - how many results you want returned.

```
$select = ['*' => 'profiles'];
$where = ['id' => 1];
$order = ['id' => 'DESC'];
$limit = 1;

$results = $db->read($select, $where, $order, $limit);
```

###### Join Clauses
You can also create a `JOIN` query by passing along an array which has a bit more advanced order. First value should be which way to join and with what table `'LEFT/RIGHT' => 'beers'`. Second has to be a new array with what rows and values should match. This is done so you can loop through several conditions `array('profiles.locationId' => 'beers.locationId')`.

```
$select = ['*' => 'beers'];
$onClause = ['beers.locationId' => 'location.id'];
$join = ['left' => 'locations', $onClause];

$results = $db->read($select, null, null, null, $join);
```

###### Results
Loop through your results, or if you are only expecting one result to return, be aware that it is still stored in a multidimensional array. You would get the result of single returns by going into the first array position in the list (`0`).

```
foreach ($results as $result){
  echo 'Timestamp: '.$result['time'];
  echo 'Recorded value: '.$result['currentValue'];
}
```

OR, if you only have one value, you can fetch it like this;

    echo $results[0]['id'];
    
    
##### Update
Takes 3 parameters, 2 is required!: `string $table`, `array $data` and `array $where`.

`$table` is a string value of which table to update in. 

`$data` is an array of 'SET [row] = [value]'. This is done so you can update several rows at once: `'umail' => 'new@email.com', 'fullName' => 'Jane Doe'`.

`$where` is an array of 'WHERE [row] = [value]': `'id' => 1`.

```
$table = 'profiles';
$data = ['umail' => 'new@email.com', 'fullName' => 'Jane Doe'];
$where = ['id' => 1];

$db->update($table, $data, $where);
```


##### Delete
Takes 2 parameters, both are required!: `string $table` and `array $where`.

`$table` is a string value of which the table to delete from.

`$where` is an array of 'WHERE [row] = [value]': `'id' => 1`.

```
$table = 'profiles';
$where = ['id' => 1];

$db->delete($table, $where);
```

&nbsp;

### Helper Classes
Just a few random helpers available. More should be added along the way. 

##### Singleton
The singleton holds one method to help in the view. A method that will return an _n_-amounth of `&nbsp;` - which is equal to a space (` `).

    echo 'Give me some distance, mayn!' .$singleton->spaces(10). 'Okay then';

##### Validators
Validate common data. `Email`, `Url`, `Ip` and `Integer`. The methods returns a Boolean value (True/False).


```
$val = new Validators();

$mailVal = $val->valMail('john@doe.com');
$urlVal = $val->valUrl('http://example.com');
$ipVal = $val->valIp('127.0.0.1');
$intVal = $val->valInt(42);
```
    

