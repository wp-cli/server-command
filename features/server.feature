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

  Scenario: Passthrough arguments to PHP binary
    Given a WP install
    And a file "mem.php" with:
      """
      <?php echo ini_get('memory_limit'); ?>
      """
    And I launch in the background `wp server --host=localhost --port=8182 -- -dmemory_limit=256M`

    When I run `curl -sS localhost:8182/mem.php`
    Then STDOUT should be:
      """
      256M
      """
