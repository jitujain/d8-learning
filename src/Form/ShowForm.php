<?php

/**
 * @file
 * This is the module to create movies database structure.
 */

namespace Drupal\inno_practice\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ShowForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'show_value_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $callService = \Drupal::service('inno_practice.custom_logging');
    $data['title'] = 'Show Form Called';
    $data['log_type'] = 'Custom Form';
    $data['data'] = [
      'form_route' => 'inno_practice.show_value_form',
      'form_desc' => 'Show third field when value of second field is Show',
    ];

    $callService->saveCustomLogs($data);

    $form['first_field'] = array(
      '#type' => 'textfield',
      '#title' => t('First Field:'),
      '#required' => FALSE,
    );

    $form['second_field'] = array(
      '#type' => 'textfield',
      '#title' => t('Second Field:'),
      '#required' => FALSE,
    );

    $form['third_field'] = array(
      '#type' => 'textfield',
      '#title' => t('Third Field:'),
      '#required' => FALSE,
      '#states' => array(
        'visible' => array(
          ':input[name="second_field"]' => array('filled' => true),
        ),
        'invisible' => array(
          array(
            ':input[name="second_field"]' => array('filled' => true),
            ':input[name="second_field"]' => array('value' => 'show'),
          ),
          array(
            ':input[name="second_field"]' => array('filled' => false),
          )
        ),
      ),
    );

    $form['fourth_field'] = array(
      '#type' => 'textfield',
      '#title' => t('Fourth Field:'),
      '#required' => FALSE,
    );


    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
