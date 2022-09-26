
## 👩‍💻 Project explanation

We want you to implement a REST API endpoint that given a list of products, applies some discounts to them
and can be filtered.

You are free to choose whatever language and tools you are most comfortable with. However, in case you
choose PHP, please use Symfony framework. Please add instructions on how to run it and publish it in
Github.

## 🎯 Given That

- Products in the boots category have a 30% discount.
- The product with sku = 000003 has a 15% discount.
- When multiple discounts collide, the biggest discount must be applied.

## 🚀 Environment Setup

- PHP 8.1
- Symfony 6.1
- Docker

### Installation

```bash
make install
```

### ✍️ Solution Explanation

The current solution are using Doctrine as ORM and Behat testing to functional.  

The discounts are applied to products using a Middelware pattern on save products. It's scalable and easy, you can add new discounts very quickly.

If you wanna improve the performance, I recommend to use ElasticSearch or Algolia for the product filtering inside the GET product list endpoint.

In the GET a single product endpoint, we can use Redis in key::value mode to save the product as cache.

We can use preSave callbacks inside Doctrine ORM to sync all Redis or ElasticSearch on any product change.

The bulk product list endpoint can be improved using an Async Job Queue. The product list can be splinted in Jobs with 5000 products each one.  

![Proposal Architecture](doc/mytheresa-challenge.png?raw=true "Proposal Architecture")
   
### ✅ Tests execution

I am using behat in order to make the functional test. 

```bash
make behat

7 scenarios (7 passed)
31 steps (31 passed)
0m0.86s (38.31Mb)
```


### 🛠️ Folder structure

```scala
$ tree -L 4 src

src
├── Core // Bounded Context: Features related to one of the company business discounts / products
│   ├── Application
│   │   ├── DiscountRules // Product Discount Ruleas folder
│   │   │   ├── DiscountRuleInterface.php
│   │   │   ├── GetDiscountInProductsWithBootsCategory.php
│   │   │   └── GetDiscountInProductsWithSkuEquals3.php
│   │   └── UseCase  // Inside the application layer all is structured by actions
│   │       ├── AddProductListUseCase.php
│   │       ├── AddProductUseCase.php
│   │       ├── CalculateProductDiscountUseCase.php
│   │       ├── GetProductListUseCase.php
│   │       └── GetProductUseCase.php
│   ├── Domain
│   │   ├── Entity
│   │   │   ├── Price.php
│   │   │   └── Product.php
│   │   ├── Exception
│   │   │   ├── ProductAlreadyExistsException.php
│   │   │   ├── ProductBadRequestException.php
│   │   │   └── ProductNotFoundException.php
│   │   ├── Filter
│   │   │   ├── ProductFilterInterface.php
│   │   │   └── ProductFilter.php
│   │   ├── Repository // The `Interface` of the repository is inside Domain
│   │   │   └── ProductRepositoryInterface.php
│   │   └── Request
│   │       ├── AddProductListRequest.php
│   │       ├── AddProductRequest.php
│   │       └── GetProductRequest.php
│   ├── Infrastructure
│   │   ├── Persistence
│   │   │   └── Doctrine
│   │   ├── Resources
│   │   │   └── config
│   │   ├── Serializer
│   │   │   └── PriceNormalizer.php
│   │   └── Tests
│   │       └── Behat // Behat context related with the company bounded context
│   └── Ui
│       └── Http
│           ├── AddProductListController.php
│           ├── GetProductController.php
│           └── GetProductListController.php
└── Shared // Endpoint controllers
    └── Infrastructure
        ├── Filter
        │   ├── FilterResponse.php
        │   ├── PaginateInterface.php
        │   └── Paginate.php
        ├── Hateoas // Hateoas representation used to list endpoints
        │   ├── PageResume.php
        │   └── Representation.php
        ├── Resources
        │   └── config
        ├── Serializer
        │   └── RepresentationNormalizer.php
        ├── Service
        │   └── ResponseService.php
        └── Tests
            └── Behat // Shared behat context
```

### POST /products

To add a product bulk inside the system.

```sh
curl --location --request POST 'http://localhost:8080/products' \
--header 'Content-Type: application/json' \
--data-raw '{
         "products": [
              {
                  "sku": "000001",
                  "name": "BV Lean leather ankle boots",
                  "category": "boots",
                  "price": 89000
              },
              {
                  "sku": "000002",
                  "name": "BV Lean leather ankle boots",
                  "category": "boots",
                  "price": 99000
              }
        ]
      }'
```

### GET /products/{sku}

I understand that the Sku of a product is the product ID. 
This endpoint return the product with the given sku. 

```sh
curl --location --request GET 'http://localhost:8080/products/000005'
```

The response is:
```json
{
    "sku": "000005",
    "name": "Nathane leather sneakers",
    "category": "sneakers",
    "price": {
        "original": 59000,
        "final": 59000,
        "discount_percentage": null,
        "currency": "EUR"
    }
}
```

### GET /products

This endpoint return all filtered products. It implements the HATEOAS in order 
to paginate the results and improve the performance.

By default the endpoint return only 10 products. You can change that using the size parameter.
 
NOTE: The priceLessThan parameter is integer, if you are looking prices less than 100.00€ would be 10000

| Parameter        | Type   | Default  | Description                |
| -------------    |:------:|:--------:| --------------------------:|
| size             | int    | 10       | nº items to return         |
| page             | int    | 1        | page number                |
| category         | string |          | product category           |
| priceLessThan    | int    |          | price before discounts   |



```sh
curl --location --request GET 'http://localhost:8080/products?size=3&page=1'
```

The response is:

```json
{
    "_links": {
        "first": {
            "href": "http://localhost:8080/products?size=3&page=1"
        },
        "self": {
            "href": "http://localhost:8080/products?size=3&page=1"
        },
        "next": {
            "href": "http://localhost:8080/products?size=3&page=2"
        },
        "last": {
            "href": "http://localhost:8080/products?size=3&page=2"
        }
    },
    "resume": {
        "page": 1,
        "size": 3,
        "totalPages": 2,
        "totalElements": 5
    },
    "response": {
        "items": [
            {
                "sku": "000001",
                "name": "BV Lean leather ankle boots",
                "category": "boots",
                "price": {
                    "original": 89000,
                    "final": 62300,
                    "discount_percentage": "30%",
                    "currency": "EUR"
                }
            },
            {
                "sku": "000002",
                "name": "BV Lean leather ankle boots",
                "category": "boots",
                "price": {
                    "original": 99000,
                    "final": 69300,
                    "discount_percentage": "30%",
                    "currency": "EUR"
                }
            },
            {
                "sku": "000003",
                "name": "Ashlington leather ankle boots",
                "category": "boots",
                "price": {
                    "original": 71000,
                    "final": 49700,
                    "discount_percentage": "30%",
                    "currency": "EUR"
                }
            }
        ]
    }
}
```


