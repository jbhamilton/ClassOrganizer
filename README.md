<h1>PHP Class Organizer - Put some order to your Classes</h1>

<ul>
    <li>Order your PHP Class files variables and functions by ascending or descending order</li>
    <li>Group private and public functions first or last with variables always at the top of the file.</li>
    <li>Specify the number of newlines to occur after each variable or function independently</li>
</ul>

<p>Organized files are by default written with a convetion like [filename].organized.php</p>


<h2>CLI use</h2>

<b>Available Options</b>
Function  | Values | Achieves
-o_| 'asc'\n'desc' | Set the sort to Ascending or Descending
-g | 'public-private'\n'private-public' | Set which order variables and function should be printed in
-fg | integer | Set the number of new lines which should be printed after functions
-vg | integer | Set the number of new lines which should be printed after variables 
-n | string | Set the name of the file the organized class should be written to (overriding any conventions)
-c | string | Set the naming convention for organized files


```bash
php ClassOrganizer.php your-class-file.php
```

<h2>PHP use</h2>

<b>Available Options</b>
Function  | Values | Achieves
set_order_| 'asc'\n'desc' | Set the sort to Ascending or Descending
set_group | 'public-private'\n'private-public' | Set which order variables and function should be printed in
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
    ->set_function_gap(0)
    ->set_variable_gap(0)
    ->set_convention('dummy')
    ->file($argv[1])
    ->organize()
    ->write();
```

