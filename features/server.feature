@require-php-7.2
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

  Scenario: Passthrough arguments to PHP binary
    Given a WP install
    And a mem.php file:
      """
      <?php echo ini_get('memory_limit'); ?>
      """
    And I launch in the background `wp server --host=localhost --port=8182 -- -dmemory_limit=256M`

    When I run `curl -sS localhost:8182/mem.php`
    Then STDOUT should be:
      """
      256M
      """

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
    And I run `wp rewrite flush`

    When I run `curl -sSL http://localhost:8183/hello-world/`
    Then STDOUT should contain:
      """
      Hello world!
      """

  Scenario: Adapt HTTPS scheme to HTTP
    Given a WP install
    And a wp-content/mu-plugins/test-https-url.php file:
      """
      <?php add_filter( 'wp_head', function() { echo '<meta name="test-https" content="https://localhost:8184/wp-content/uploads/test.jpg">'; } );
      """
    And I run `wp option update home https://localhost:8184`
    And I run `wp option update siteurl https://localhost:8184`
    And I launch in the background `wp server --host=localhost --port=8184 --adapt-scheme`

    When I run `curl -sS localhost:8184`
    Then STDOUT should contain:
      """
      http://localhost:8184/wp-content/uploads/test.jpg
      """
    Then STDOUT should not contain:
      """
      https://localhost:8184
      """
