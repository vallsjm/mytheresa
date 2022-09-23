Feature: As a API mytheresa
  I want to have an API product endpoint
  So I can get the data of a single product knowing its sku

  Scenario: I can retrieve the information of a valid product sku
    Given there are the following products:
      | Sku     | Name                          | Category  | Price  |
      | 000001  | BV Lean leather ankle boots   | boots     | 89000  |
    When I add header "Content-Type" with "application/json"
    And I send a "GET" request to "/products/000001"
    Then the response status code should be 200
    And the response should be in JSON and contain:
      """
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
      }
      """

  Scenario: I can't retrieve the information from non-existent valid product sku
    When I add header "Content-Type" with "application/json"
    And I send a "GET" request to "/products/000010"
    Then the response status code should be 404