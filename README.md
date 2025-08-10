# MT Code Challenge - Javier Sagra

## Decisions

Decided to use PHP and Symfony in order to create a DDD application with a couple of Bounded Contexts, one for `Product` and another one for the `SharedKernel`.

The main `Product` bounded context has the three layers:

- Application: in charge of receiving the requests from Infrastructure and orchestrate calls to Domain (through interfaces) and return results to Infrastructure.
- Domain: Has the Business Logic and decisions regarding it. Also has the interfaces needed in the other two layers.
- Infrastructure: Receives the requests from outside through the Controller, generates a Query which is dispatched and returns the result to outside. Also interacts with database (Doctrine) and cache (Redis).

Redis has been added in order to cache some data and don't query as much the database, but it's only storing the discounts for Product and for Category. Products are not stored in Redis because we need to filter by Category and by Price. We could use ZRANGE for filtering being price the score, but we would not be able to filter by category and by price less than.

You can change discounts directly on database, but make sure to wait 120 seconds in order to expire the existing Redis key. Or execute `make flush`, as described later.

A much better solution could be to use ElasticSearch or OpenSearch, we could order by price, limit results by price and filter by category with a good performance. And in order to ingest products, we could publish an event on creating or updating the product, and then an EventDispatcher would reingest it. But didn't have time to implement OpenSearch in context of this challenge.

## How to run the Application

### Raise the environment

This repository makes use of the `make` tool for helping with the most common actions.

In order to raise the application, clone the repository wherever you want and execute the following command:

```
make dev
```

Once the command execution finishes, you should be able to use the application. Using the `make dev` command is important, because it does the following:

- Does the `docker compose up -d`
- Creates the database
- Creates the doctrine schema
- Imports all the data from JSON files
- Flushes Redis cache

There are other `make` commands available:

| Command      | Used for |
| ----------- | ----------- |
| make help | Show a brief help of all the commands available |
| make dev | compose up dev environment and apply JSON fixtures |
| make nodev | compose down dev environment |
| make shell | enter the PHP container |
| make unit | run unit tests |
| make integration | run integration tests |
| make test | run ALL tests (unit and integration) |
| make cache | execute cache:clear |
| make flush | flush Redis cache |
| make tree | show git log tree |
| make purge | removes ALL docker containers, images and volumes in dev machine |

### Execute Tests

In order to execute Unit and Functional tests, you can use the following commands (as described above):

Unit Tests

```
make unit
```

Functinoal Tests

```
make functional
```

Unit + Functional Tests

```
make test
```

### Change the default data

On the application's root directory there's a folder called `fixtures` with JSON files with the default provided data. On `make dev`, files `products.json` and `discounts.json` will be used to populate the database.

`products_original.json` has the original data from requirements, whilst `products_long.json` has ~100 products to test.

If you want to change the default data, change the two main files, then first execute `make nodev`, change the files and execute `make dev` again.

### Test the Application

Once the application has started, it exposes port `80` for receiving requests.

> :warning: If you have any other service listening in port `80`, you will have to stop it.

The base URL for the application is http://localhost

The only endpoint exposed is 

```
GET /products
```

There are three query parameters available:

- `category`: filters results by category
- `priceLessThan`: retrieves only results with original price less than or equals the provided
- `page`: if given, returns that page of results. If not given, returns first page of 5.

This application can be tested using [Postman](https://www.postman.com/) or a similar tool, but you can also use `curl` from the command line to test it.

CURL syntax example:

```
curl --location 'http://localhost/products?category=boots&priceLessThan=89000&page=1'
```
