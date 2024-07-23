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

### Crud adapted from js-sdk to php

**Returns a paginated records list.**
```php
$pb->collection(collectionIdOrName)->getList(int $page = 1, int $perPage = 30, array $queryParams = []);
```

**Returns a list with all records batch fetched at once.**
```php
$pb->collection(collectionIdOrName)->getFullList(int $batch = 200, array $queryParams = []);
```

**Returns the first found record matching the specified filter.**
```php
$pb->collection(collectionIdOrName)->getFirstListItem(string $filter, array $queryParams = []);
```

**Returns a single record by its id.**
```php

$pb->collection(collectionIdOrName)->getOne(string $recordId, array $queryParams = []);
```

**Creates (aka. register) a new record.**
```php
$pb->collection(collectionIdOrName)->create(array  $bodyParams = [], array $queryParams = []);
```
```json
{
  "name" : "Algerie"
}
```

**Updates an existing record by its id.**

URL : `?id=y0eirw25xxxxxxxx`
```php
$pb->collection(collectionIdOrName)->update(string $recordId, array $bodyParams = [],array $queryParams = []);
```
```json
{
  "name" : "Lebanon"
}
```

**Deletes a single record by its id.**
```php
$pb->collection(collectionIdOrName)->delete(string $recordId, array $queryParams = []);
```

**Custom Logic**
```php
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
- Pocketbase : http://localhost:8185/_/#/
- API endpoints : https://web.postman.co/workspace/IFX-Dev.~66822cb4-279f-4aeb-8486-23506f9657f0/collection/18647677-2f88e286-0b5b-49d8-9f2f-fba7616f85c4 
