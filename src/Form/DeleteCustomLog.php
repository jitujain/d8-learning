<?php

namespace Drupal\inno_practice\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\AjaxFormHelperTrait;

/**
 * Defines a confirmation form to confirm deletion of something by id.
 */
class DeleteCustomLog extends ConfirmFormBase {

  use AjaxFormHelperTrait;

  /**
   * ID of the item to delete.
   *
   * @var int
   */
  protected $id;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $log_id = null) {
    $this->id = $log_id;

    $form = parent::buildForm($form, $form_state);
    $form['actions']['submit']['#ajax']['callback'] = '::ajaxSubmit';
    $form['actions']['cancel']['#attributes']['class'][] = 'dialog-cancel';

    return $form;
  }

  /**
   * AJAX callback handler that displays any errors or a success message.
   */
  public function successfulAjaxSubmit(array $form, FormStateInterface $form_state) {

    $response = new AjaxResponse();

    try {
      $query = \Drupal::database()->delete('custom_logging');
      $query->condition('lid', $this->id);
      $query->execute();
    }
    catch (Exception $e) {

    }

    $response->addCommand(new RedirectCommand(Url::fromRoute('inno_practice.show_custom_logs')->toString()));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return "confirm_delete_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('inno_practice.show_custom_logs');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Do you want to delete %id?', ['%id' => $this->id]);
  }
}
