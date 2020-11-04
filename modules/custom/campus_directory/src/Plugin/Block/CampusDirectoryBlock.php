<?php

namespace Drupal\campus_directory\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\campus_directory\Classes\CampusDirectory;

/**
 * Provides a 'Campus Directory' Block.
 *
 * @Block(
 *   id = "campus_directory_block",
 *   admin_label = @Translation("Campus Directory Block"),
 *   category = @Translation("Custom Integrations"),
 * )
 */
class CampusDirectoryBlock extends BlockBase
{

    // https://www.drupal.org/docs/8/creating-custom-modules/creating-custom-blocks/create-a-custom-block

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $config = $this->getConfiguration();
        if (!empty($config['campus_directory_block_config'])) {
            $name = $config['campus_directory_block_config'];
        } else {
            $name = $this->t('kagarwal');
        }

        return [
            '#theme' => 'campus_directory_block',
            // '#title' => $name,
            '#cruzids' => CampusDirectory::getCampusDirData($name)
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state)
    {
        $form = parent::blockForm($form, $form_state);

        $config = $this->getConfiguration();

        $form['campus_directory_block_config'] = [
            '#type' => 'textfield',
            '#title' => $this->t('List of CruzIDs'),
            '#description' => $this->t('List the cruzIDs you want present in your Campus directory block.'),
            '#default_value' => isset($config['campus_directory_block_config']) ? $config['campus_directory_block_config'] : '',
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state)
    {
        parent::blockSubmit($form, $form_state);
        $values = $form_state->getValues();
        $this->configuration['campus_directory_block_config'] = $values['campus_directory_block_config'];
    }
}
