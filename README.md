<h1>PHP Class Organizer - Put some order to your Classes</h1>

<ul>
    <li>Order your PHP Class files variables and functions by ascending or descending order</li>
    <li>Group private and public functions first or last with variables always at the top of the file.</li>
    <li>Specify the number of newlines to occur after each variable or function independently</li>
</ul>

<h2>CLI use</h2>

```bash
php ClassOrganizer.php your-class-file.php
```

<h2>PHP use</h2>

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

