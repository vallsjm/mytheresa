Feature: As a API mytheresa
  I want to have an API product endpoint
  So I can set the data of a product list

  Scenario: I wanna add product list
    Given I add header "Content-Type" with "application/json"
    When I send a "POST" request to "/products" with body:
      """
      {
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
              },
              {
                  "sku": "000003",
                  "name": "Ashlington leather ankle boots",
                  "category": "boots",
                  "price": 71000
              },
              {
                  "sku": "000004",
                  "name": "Naima embellished suede sandals",
                  "category": "sandals",
                  "price": 79500
              },{
                  "sku": "000005",
                  "name": "Nathane leather sneakers",
                  "category": "sneakers",
                  "price": 59000
              }
        ]
      }
      """
    Then the response status code should be 200
