@require-php-5.4
Feature: Serve WordPress locally

  Scenario: Vanilla install
    Given a WP install
    And I launch in the background `wp server --host=localhost --port=8181`
    And I run `wp option set blogdescription 'Just another WordPress site'`

    When I run `curl -sS localhost:8181`
    Then STDOUT should contain:
      """
      Just another WordPress site
      """

    When I run `curl -sS localhost:8181/license.txt > /tmp/license.txt`
    And I run `cmp /tmp/license.txt license.txt`
    Then STDOUT should be empty

  Scenario: Access wp-login.php
    Given a WP install
    And I launch in the background `wp server --host=localhost --port=8182`

    When I run `curl -sS http://localhost:8182/wp-login.php`
    Then STDOUT should contain:
      """
      wp-login.php
      """

  Scenario: Pretty permalinks
    Given a WP install
    And I launch in the background `wp server --host=localhost --port=8183`
    And I run `wp option update permalink_structure '/%postname%/'`

    When I run `curl -sS http://localhost:8183/?p=1`
    Then STDOUT should contain:
      """
      Hello world!
      """
