# Pocketbase

### Open Source backend for your next SaaS and Mobile app in 1 file

- https://pocketbase.io/
- https://pocketbase.io/docs/
- Integration with docker using : https://pocketbase.io/docs/going-to-production/#using-docker
- PHP SDK codebase : https://gitlab.jonathan-martz.de/softwareentwicklung/pocketbase-php-sdk

---

``` php
// Example init and use
use \Pb\Client as pb;
$pb = new pb('https://name_of_your_pocketbase_container_name:8080');
var_dump($pb->collection('countries')->getList());
```

***
### Crud adapted from js-sdk to php

``` php
// Returns a paginated records list.
$pb->collection(collectionIdOrName)->getList(int $page = 1, int $perPage = 30, array $queryParams = []);

// Returns a list with all records batch fetched at once.
$pb->collection(collectionIdOrName)->getFullList(int $batch = 200, array $queryParams = []);

// Returns the first found record matching the specified filter.
$pb->collection(collectionIdOrName)->getFirstListItem(string $filter, array $queryParams = []);

// Returns a single record by its id.
$pb->collection(collectionIdOrName)->getOne(string $recordId, array $queryParams = []);

// Creates (aka. register) a new record.
$pb->collection(collectionIdOrName)->create(array  $bodyParams = [], array $queryParams = []);

// Updates an existing record by its id.
$pb->collection(collectionIdOrName)->update(string $recordId, array $bodyParams = [],array $queryParams = []);

// Deletes a single record by its id.
$pb->collection(collectionIdOrName)->delete(string $recordId, array $queryParams = []);

// Custom Logic
$pb->collection(collectionIdOrName)->upload(string $recordId, string $field, string $filepath);
```
---

### Migrations

- https://pocketbase.io/docs/js-migrations
- Connect to `pktb_pocketbase`

```bash
/ # cd pb/
/pb # ls
CHANGELOG.md   LICENSE.md     pb_data        pb_migrations  pocketbase
/pb # ./pocketbase  migrate create "add_country_code"
? Do you really want to create migration "pb_migrations/1721331064_add_country_code.js"? Yes
Successfully created file "pb_migrations/1721331064_add_country_code.js"
/pb # 
```
### App

- App : http://localhost:8085/
- Pocketbase : http://localhost:8185/

```

/var/www/html/index.php:15:
array (size=7)
  0 => string 'FRANCE' (length=6)
  1 => string 'ENGLAND' (length=7)
  2 => string 'PORTUGAL' (length=8)
  3 => string 'SPAIN' (length=5)
  4 => string 'BELGIUM' (length=7)
  5 => string 'TURKEY' (length=6)
  6 => string 'ITALY' (length=5)

/var/www/html/index.php:19:
array (size=7)
  0 => string 'Ronaldo' (length=7)
  1 => string 'Messi' (length=5)
  2 => string 'Benzema' (length=7)
  3 => string 'Vini jr' (length=7)
  4 => string 'Kane' (length=4)
  5 => string 'Rodri' (length=5)
  6 => string 'Kroos' (length=5)

```