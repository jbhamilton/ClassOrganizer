<h1>PHP Class Organizer - Put some order to your Classes</h1>

<ul>
    <li>Order your PHP Class files variables and functions by ascending or descending order</li>
    <li>Group private and public functions first or last with variables always at the top of the file.</li>
    <li>Specify the number of newlines to occur after each variable or function independently</li>
</ul>

<p>Organized files are by default written with a convetion like [filename].organized.php</p>


<h2>PHP use</h2>

<b>Available Options</b>

Function  | Values | Achieves
--------- | ------ | --------
set_order_| 'asc'</br>'desc' | Set the sort to Ascending or Descending
set_group | 'public-private'</br>'private-public' | Set which order variables and function should be printed in
set_function_gap | integer | Set the number of new lines which should be printed after functions
set_variable_gap | integer | Set the number of new lines which should be printed after variables 
set_out_name | string | Set the name of the file the organized class should be written to (overriding any conventions)
set_convention | string | Set the naming convention for organized files
file | string | Specify the file which should be organized 
organize | none | Organize the specified file
write | none | Write the organized file


```php

$CO = new ClassOrganizer();

$CO->set_group('public-private')
    ->set_order('desc')
    ->set_function_gap(3)
    ->set_variable_gap(1)
    ->set_convention('organized-file')
    ->file($argv[1])
    ->organize()
    ->write();

//OR

new ClassOrganizer('file.php');

```

<hr>

<h2>CLI use</h2>

<b>Available Options</b>

Flag | Values | Achieves
--------- | ------ | --------
-o | 'asc'</br>'desc' | Set the sort to Ascending or Descending
-g | 'public-private'</br>'private-public' | Set which order variables and function should be printed in
-fg | integer | Set the number of new lines which should be printed after functions
-vg | integer | Set the number of new lines which should be printed after variables 
-n | string | Set the name of the file the organized class should be written to (overriding any conventions)
-c | string | Set the naming convention for organized files


```bash
##BASIC
php ClassOrganizer.php your-class-file.php

##FLAGS
php ClassOrganizer.php your-class-file.php -o desc -n new-file.php -fg 5 -vg 3
```


