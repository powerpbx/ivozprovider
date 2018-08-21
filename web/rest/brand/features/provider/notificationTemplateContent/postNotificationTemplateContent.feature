Feature: Create notification template contents
  In order to manage notification template contents
  As an super admin
  I need to be able to create them through the API.

  @createSchema
  Scenario: Create an notification template content
    Given I add Authorization header
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/notification_template_contents" with body:
    """
      {
          "language": 2,
          "notificationTemplate": 1,
          "subject": "Test subject",
          "body": "Test body"
      }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON should be equal to:
    """
      {
          "fromName": null,
          "fromAddress": null,
          "id": 2
      }
    """

  Scenario: Retrieve created notification template content
    Given I add Authorization header
    When I add "Accept" header equal to "application/json"
    And I send a "GET" request to "notification_template_contents/2"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON should be equal to:
    """
      {
          "fromName": null,
          "fromAddress": null,
          "subject": "Test subject",
          "body": "Test body",
          "id": 2,
          "notificationTemplate": {
              "name": "Voicemail notification",
              "type": "voicemail",
              "id": 1,
              "brand": 1
          },
          "language": {
              "iden": "en",
              "id": 2,
              "name": {
                  "en": "en",
                  "es": "en"
              }
          }
      }
    """
