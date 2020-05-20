<?php

/**
 * @file
 * This is the module to create movies database structure.
 */

namespace Drupal\inno_practice\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * Provides route responses for the Example module.
 */
class InnoCustomDeatilLog extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function showCustomDetailLog($log_id) {


    $response = new AjaxResponse();

    $query = \Drupal::database()->select('custom_logging', 'c');
    $query->addField('c', 'data', 'data');
    $query->condition('c.lid', $log_id);
    $query->orderBy('c.created', 'DESC');
    $logdata = $query->execute()->fetchField();

    if (!empty($logdata)) {
      $datas = unserialize($logdata);
      $data = json_encode($datas);
    }
    else {
      $data = t('No Data Found for this log.');
    }

    // Add an AJAX command to open a modal dialog with the form as the content.
    $response->addCommand(new OpenModalDialogCommand('My Modal Form', $data, ['width' => '800']));

    return $response;
  }
}
