<?php

namespace Drupal\inno_practice\Controller;

use Drupal\Core\Database\Driver\mysql\Connection;

/**
* Class MyTools.
*/
class CustomLogging {
 /*
  * @var \Drupal\Core\Database\Connection $database
  */
 protected $database;

 /**
  * Constructs a new MyTools object.
  * @param \Drupal\Core\Database\Connection $connection
  */
 public function __construct(Connection $connection) {
   $this->database = $connection;
 }

 /**
  * Show the author of the node.
  *
  * @param int $nid
  * The node id.
  *
  * @return int
  * Return the uid.
  */
  public function showAuthor ($nid) {
    $query = $this->database->select('node_field_data', 'nfd');
    $query->condition('nfd.nid', $nid);
    $query->fields('nfd', ['uid']);
    $result = $query->execute()->fetchAll();
    if (!empty($result)) {
      return $result[0]->uid;
    }
  }

  public function saveCustomLogs($data) {
    $current_user = \Drupal::currentUser();
    $this->createCustomLoggingTable();
    if (!empty($data) && is_array($data)) {
      try {
        $result = $this->database->insert('custom_logging')
        ->fields([
          'title' => isset($data['title']) ? $data['title'] : null,
          'log_type' => isset($data['log_type']) ? $data['log_type'] : null,
          'uid' => $current_user->id(),
          'created' => time(),
          'data' => isset($data['data']) ? serialize($data['data']) : null,
        ])
        ->execute();
      }
      catch (Exception $e) {

      }
    }
  }

  public function createCustomLoggingTable() {
    $schema = $this->database->schema();
    $table_name = 'custom_logging';

    if (!$schema->tableExists($table_name)) {

      $table_schema = [
        'fields' => [
          'lid' => [
            'type' => 'serial',
            'size' => 'big',
            'not null' => TRUE,
          ],
          'title' => [
            'type' => 'varchar',
            'not null' => TRUE,
            'length' => 25,
          ],
          'log_type' => [
            'type' => 'varchar',
            'not null' => FALSE,
            'length' => 25,
          ],
          'uid' => [
            'type' => 'int',
            'not null' => FALSE,
          ],
          'created' => [
            'type' => 'int',
            'not null' => FALSE,
          ],
          'data' => [
            'type' => 'blob',
            'not null' => FALSE,
          ],
        ],
        'primary key' => ['lid'],
      ];

      $schema->createTable($table_name, $table_schema);
    }
  }
}
