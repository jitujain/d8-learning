<?php

namespace Drupal\inno_practice\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 *
 */
class InnoCustomLogs extends ControllerBase {

  function showCustomLogs() {

    $data = $this->innoGetCustomLogs();

    $headers = [
      'title' => 'Title',
      'log_type' => 'Log Type',
      'created' => 'Created',
      'view' => 'Detail View',
      'delete' => 'Delete View',
    ];

    $rows = [];
    foreach ($data as $key => $value) {
      $row['title'] = $value->title;
      $row['log_type'] = $value->log_type;
      $row['created'] = date('M d, Y', $value->created);

      $link_url = Url::fromRoute('inno_practice.show_custom_detail_log', ['log_id' => $value->lid], [
        'attributes' => [
          'class' => ['use-ajax'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => '{&quot;width&quot;:400}',
        ],
        '#attached' => ['library' => ['core/drupal.dialog.ajax']]
      ]);

      $row['view'] = Link::fromTextAndUrl(t('Open modal'), $link_url)->toString();

      // Delete Link.
      $dlink_url = Url::fromRoute('inno_practice.delete_log', ['log_id' => $value->lid], [
        'attributes' => [
          'class' => ['use-ajax', 'button', 'button--small'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => '{&quot;width&quot;:400}',
        ],
        '#attached' => ['library' => ['core/drupal.dialog.ajax']]
      ]);

      $row['delete'] = Link::fromTextAndUrl(t('Delete Record'), $dlink_url)->toString();

      $rows[] = $row;
    }

    $output[] =  [
      '#theme' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#attached' => ['library' => ['core/drupal.dialog.ajax']]
    ];

    $output[] =  [
      '#type' => 'pager',
    ];

    return $output;
  }

  function innoGetCustomLogs() {

    // Get the movies data.
    $query = \Drupal::database()->select('custom_logging', 'c');
    $query->addField('c', 'title', 'title');
    $query->addField('c', 'lid', 'lid');
    $query->addField('c', 'log_type', 'log_type');
    $query->addField('c', 'created', 'created');
    $query->orderBy('c.created', 'DESC');
    //For the pagination we need to extend the pagerselectextender and
    //limit in the query
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(2);
    $results = $pager->execute()->fetchAll();

    return $results;
  }
}
