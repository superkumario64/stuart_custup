<?php

namespace Drupal\campus_directory\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Entity\Menu;

use function PHPSTORM_META\type;

/**
 * Configure Campus Directory for this site.
 */
class CampusDirectoryConfigurationForm extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'campus-directory_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'campus_directory.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('campus_directory.settings');


    $form['lpad_password'] = [
      '#type' => 'textfield',
      '#title' => $this->t('LDAP Password'),
      '#description' => $this->t('Password for ldap user.'),
      '#default_value' => $config->get('lpad_password'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    // echo "<pre>";
    // echo "HELLOOO????";
    // echo "</pre>";die;
    $this->config('campus_directory.settings')
      ->set('lpad_password', $values['lpad_password'])
      ->save();

    parent::submitForm($form, $form_state);
  }
}
