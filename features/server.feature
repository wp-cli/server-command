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

    When I run `curl -sS localhost:8182/wp-login.php`
    Then STDOUT should contain:
      """
      <form name="loginform"
      """

  Scenario: Pretty permalinks with posts
    Given a WP install
    And I launch in the background `wp server --host=localhost --port=8183`
    And I run `wp rewrite structure '/%postname%/' --hard`
    And I run `wp post create --post_title='Test Post' --post_status=publish --porcelain`
    And save STDOUT as {POST_ID}

    When I run `curl -sS localhost:8183/test-post/`
    Then STDOUT should contain:
      """
      Test Post
      """

  Scenario: Access wp-admin entry point
    Given a WP install
    And I launch in the background `wp server --host=localhost --port=8184`

    When I run `curl -sS -L localhost:8184/wp-admin/`
    Then STDOUT should contain:
      """
      <form name="loginform"
      """
