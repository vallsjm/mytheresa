Feature: As a API mytheresa
  I want to have an API product endpoint

  Background:
    Given there are the following products:
      | Sku     | Name                            | Category  | Price  |
      | 000001  | BV Lean leather ankle boots     | boots     | 89000  |
      | 000002  | BV Lean leather ankle boots     | boots     | 99000  |
      | 000003  | Ashlington leather ankle boots  | boots     | 71000  |
      | 000004  | Naima embellished suede sandals | sandals   | 79500  |
      | 000005  | Nathane leather sneakers        | sneakers  | 59500  |

  Scenario: I can retrieve the information of the first page of products
    When I add header "Content-Type" with "application/json"
    And I send a "GET" request to "/products?size=3&page=1"
    Then the response status code should be 200
    And the response must be a HATEAOS paginated response with the page index in 1, 3 records per page, 2 total pages and contain these items:
      """
        [
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
      """

  Scenario: I can retrieve the information of the second page of products
    When I add header "Content-Type" with "application/json"
    And I send a "GET" request to "/products?size=3&page=2"
    Then the response status code should be 200
    And the response must be a HATEAOS paginated response with the page index in 2, 3 records per page, 2 total pages and contain these items:
      """
        [
            {
                "sku": "000004",
                "name": "Naima embellished suede sandals",
                "category": "sandals",
                "price": {
                    "original": 79500,
                    "final": 79500,
                    "discount_percentage": null,
                    "currency": "EUR"
                }
            },
            {
                "sku": "000005",
                "name": "Nathane leather sneakers",
                "category": "sneakers",
                "price": {
                    "original": 59500,
                    "final": 59500,
                    "discount_percentage": null,
                    "currency": "EUR"
                }
            }
        ]
      """

  Scenario: I can retrieve the information of products with category boots
    When I add header "Content-Type" with "application/json"
    And I send a "GET" request to "/products?category=boots&size=3&page=1"
    Then the response status code should be 200
    And the response must be a HATEAOS paginated response with the page index in 1, 3 records per page, 1 total pages and contain these items:
      """
        [
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
      """

  Scenario: I can retrieve the information of products with category boots and price less than 800€
    When I add header "Content-Type" with "application/json"
    And I send a "GET" request to "/products?category=boots&priceLessThan=80000&size=3&page=1"
    Then the response status code should be 200
    And the response must be a HATEAOS paginated response with the page index in 1, 3 records per page, 1 total pages and contain these items:
      """
        [
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
      """