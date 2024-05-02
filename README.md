<p align="center">
<img alt="GitHub Workflow Status (branch)" src="https://github.com/karoldabro/time-machine/actions/workflows/laravel.yml/badge.svg">
<img alt="Packagist Version" src="https://img.shields.io/packagist/v/kdabrow/time-machine">
<img alt="Packagist Downloads" src="https://img.shields.io/packagist/dm/kdabrow/time-machine">
</p>

# Time machine
This package allows to move in time database data. It automatically selects all fields
that store datetime and move them by given period or to particular date, relatively from it current
value. See example to check more details.
## Motivation
This package might be useful in a pre-prod environment to test log lasting processes. For example 
generating customer invoice. Usually invoices are generated in a 30 days period. You might move  
customer and all it's data into previous invoice cycle (30 days in to past) and effectively simulate 
whole invoice cycle like that customer was created 30 days ago.
## Installation
First install main package:
```shell
composer require kdabrow/time-machine
```
Then install database driver package:

| Database | Driver                                                |
|----------|-------------------------------------------------------|
| mysql    | ```composer require kdabrow/time-machine-mysql ```    |
| postgres | ```composer require kdabrow/time-machine-postgres ``` |

Advice is to install those packages as --dev dependencies.
## Usage
### Time traveller setup
First create TimeTraveller. Only Eloquent model can be TimeTraveller. Basic configuration look like this:
```php
<?php

use Kdabrow\TimeMachine\TimeTraveller;
use App\Models\User;

// provide model instance
$traveller = new TimeTraveller(new User());

// or class name
$traveller = new TimeTraveller(User::class);
```
#### Modify query
TimeMachine by default selects all rows related to given to TimeTraveller model. You're able to provide your
conditions to restrict query

```php
<?php

use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Group;
use Kdabrow\TimeMachine\TimeTraveller;
use Kdabrow\TimeMachine\Result;
use Illuminate\Support\Arr;

// Result contains all previously changed models from all TimeTravellers
// For example move Users only from previously changed Group
$traveller = new TimeTraveller(
    User::class, 
    function(Builder $builder, Result $result) {
        if ($result->isSuccessful(Group::class)) {
            return $query->whereIn(
                'group_id', 
                $result->getSuccessful(Group::class)->pluck('id')
            );
        }
    }
);
```
#### Additional columns
It's possible to add additional columns into query.

```php
<?php

use App\Models\User;
use Kdabrow\TimeMachine\TimeTraveller;
use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\Result;
use Illuminate\Database\Eloquent\Model;

$traveller = new TimeTraveller(User::class);
$traveller->alsoChange('date_of_creation');

// It's possible to provide callback and determine how given field should be changed
// Column object has information about currently modified column
// Model is selected instance of User
// Result contains previously moved in time data 
// In this example only move if no errors appeared during previous time travels 
$traveller->alsoChange(
    'date_of_creation', 
    function($currentValue, Column $column, Model $model, Result $result) {
        if (count($result->getAllFailed())) {
            return $currentValue;
        }
        
        return null;
    }
);
```
#### Exclude fields
Sometimes is need to omit some fields that would usually be selected to change.
```php
<?php

use App\Models\User;
use Kdabrow\TimeMachine\TimeTraveller;

$traveller = new TimeTraveller(User::class);
$traveller->exclude('date_of_birth');
```
#### Set up keys
Records selected to time travel are based on primary key from the model. You're able to overwrite it.
```php
<?php

use App\Models\User;
use Kdabrow\TimeMachine\TimeTraveller;

$traveller = new TimeTraveller(User::class);
$traveller->setKeys(['uuid']);
```
### Time machine and direction of move
After TimeTravellers are created, create TimeMachine.
```php
<?php

use Kdabrow\TimeMachine\TimeMachine;

$timeMachine = new TimeMachine();

// now add previously created TimeTravellers
$timeMachine->take($traveller1);
$timeMachine->take($traveller2);
$timeMachine->take($traveller3);
```
#### Move to the past
First create DateChooser. It is information about period or date.
```php
<?php

use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\Result;
use DateInterval;

// move by DateInterval
$chooser = new DateChooser(new DateInterval("P1D")); // Move by 1 day

// or move by some specific amount of seconds
$chooser = new DateChooser(3600); // Move by 1 hour

// Now set up direction and start travel
/** @var Result $result */
$result = $timeMachine
    ->toPast($chooser)
    ->start();
```
#### Move to the future
First create DateChooser. It is information about period or date.
```php
<?php

use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\Result;
use DateInterval;

// move by DateInterval
$chooser = new DateChooser(new DateInterval("P1D")); // Move by 1 day

// or move by some specific amount of seconds
$chooser = new DateChooser(3600); // Move by 1 hour

// Now set up direction and start travel
/** @var Result $result */
$result = $timeMachine
    ->toFuture($chooser)
    ->start();
```
#### Move to particular date
First create DateChooser. It is information about period or date.
```php
<?php

use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\Result;
use DateTime;

// put DateTimeInterface object
$chooser = new DateChooser(new DateTime("2020-15-16 12:12:12")); // Move datetime columns to 2020-15-16 12:12:12

// or provide datetime string 
$chooser = new DateChooser("2020-15-16 12:12:12"); // Move to 2020-15-16 12:12:12

// Now set up direction and start travel
/** @var Result $result */
$result = $timeMachine
    ->toDate($chooser)
    ->start();
```

## Examples
### Move customer, it's payments and orders 10 days in the past
Database structure before and after change:  
**customers (before)**

| id  | email           | date_of_birth | activated_at        | created_at          | updated_at          |
|-----|-----------------|---------------|---------------------|---------------------|---------------------|
| 100 | tesla@test.com  | 1856-07-10    | 2000-06-15 12:12:12 | 1999-06-15 12:12:12 | 1999-06-15 12:12:12 |

**customers (after)**

| id  | email           | date_of_birth | activated_at        | created_at          | updated_at          |
|-----|-----------------|---------------|---------------------|---------------------|---------------------|
| 100 | tesla@test.com  | 1856-07-10    | 2000-06-05 12:12:12 | 1999-06-05 12:12:12 | 1999-06-05 12:12:12 |

**payments (before)**

| id  | customer_id | amount | paid_at             | created_at          | updated_at          |
|-----|-------------|--------|---------------------|---------------------|---------------------|
| 200 | 100         | 120    | 2020-03-24 13:10:45 | 2020-03-24 10:10:45 | 2020-03-24 13:10:45 |

**payments (after)**

| id  | customer_id | amount | paid_at             | created_at          | updated_at          |
|-----|-------------|--------|---------------------|---------------------|---------------------|
| 200 | 100         | 120    | 2020-03-14 13:10:45 | 2020-03-14 10:10:45 | 2020-03-14 13:10:45 |

**orders**

| id  | payment_id | name   | sent_at    | created_at          | updated_at          |
|-----|------------|--------|------------|---------------------|---------------------|
| 300 | 200        | Engine | 2020-03-25 | 2020-03-24 13:11:22 | 2020-03-25 15:32:17 |

**orders**

| id  | payment_id | name   | sent_at    | created_at          | updated_at          |
|-----|------------|--------|------------|---------------------|---------------------|
| 300 | 200        | Engine | 2020-03-15 | 2020-03-14 13:11:22 | 2020-03-15 15:32:17 |

Script:

```php
<?php

use Kdabrow\TimeMachine\TimeTraveller;
use Kdabrow\TimeMachine\Result;
use Kdabrow\TimeMachine\TimeMachine;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Arr;

$customerId = 100;

$customerTraveller = new TimeTraveller(
    Customer::class, 
    function(Builder $builder, Result $result) use ($customerId) {
        return $builder->where('id', '=', $customerId);
    }
);
$customerTraveller->exclude('date_of_birth');

$paymentTraveller = new TimeTraveller(
    Payment::class,  
    function(Builder $builder, Result $result) use ($customerId) {
        if ($result->isSuccessful(Customer::class)) {
                return $builder->whereIn(
                    'customer_id', $result->getSuccessful(Customer::class)->pluck('id')
                );
            }    
        }
);

$orderTraveller = new TimeTraveller(
    Order::class,  
    function(Builder $builder, Result $result) use ($customerId) {
        if ($result->isSuccessful(Payment::class)) {
            return $builder->whereIn(
                'payment_id', $result->getSuccessful(Payment::class)->pluck('id')
            );
        }
    }
);

$timeMachine = new TimeMachine();

$result = $timeMachine
    ->take($customerTraveller)
    ->take($paymentTraveller)
    ->take($orderTraveller)
    ->toPast(new DateInterval("P10D"))
    ->start();

// Get instances that failed time travel
$failed = $result->getAllFailed();

$sucessful = $result->getAllSuccessful();
```

## Testing
Run tests from docker container
```shell
docker-compose exec php vendor/bin/phpunit
```
or directly from your machine
```shell
vendor/bin/phpunit
```