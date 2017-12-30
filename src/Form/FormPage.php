<?php

namespace Drupal\form_page_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * {@inheritdoc}
 */
class FormPage extends FormBase {

  /**
   * @return string
   *   The unique ID of this form defined by this class
   */
  public function getFormId() {
    return 'form_page_example';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormstateInterface $form_state) {

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This is an example form that will take your name, age, gender and birthdate and display it back to you when submitted.'),
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#description' => $this->t('Enter your name.'),
      '#required' => TRUE,
    ];

    $form['birth_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Birth Date'),
      '#description' => $this->t('Enter your birth date.'),
      '#required' => TRUE,
    ];

    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
      '#description' => $this->t('Enter your age.'),
      '#required' => TRUE,
    ];

    $form['gender'] = [
      '#type' => 'select',
      '#title' => $this->t('Gender'),
      '#description' => $this->t('Choose your gender.'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
        'other' => $this->t('Other'),
      ],
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * This function will validate the form input.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Check if name input contains any invalid characters.
    $name = $form_state->getValue('name');
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
      $form_state->setErrorByName('name', $this->t('Your name should contain only letters.'));
    }

    // Validate age.
    $age = $form_state->getValue('age');
    if ($age < 7 or $age > 120) {
      $form_state->setErrorByName('age', $this->t('You are either too young or too old for using the internet.'));
    }

    // Check if age and birth date correspond.
    $birth_year = substr(($form_state->getValue('birth_date')), 0, 4);
    $current_year = date("Y");
    if (($current_year - $birth_year) != $age) {
      $form_state->setErrorByName('birth_date', $this->t('Your birth date and age do not correspond.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Capitalise the first letter of the name.
    $form_state->setValue('name', ucfirst($form_state->getValue('name')));

    // Output all the values entered by user.
    drupal_set_message($this->t('Your name is "@name", you were born on @birth_date as @gender. You are now @age years old.', [
      '@name' => $form_state->getValue('name'),
      '@birth_date' => $form_state->getValue('birth_date'),
      '@gender' => $form_state->getValue('gender'),
      '@age' => $form_state->getValue('age'),
    ]));
  }

}
