<?php

namespace Drupal\cd_integration\Controller;

// do some https://api.drupal.org/api/drupal/core!lib!Drupal.php/function/Drupal%3A%3AentityQuery/8.2.x


use Drupal\Core\Controller\ControllerBase;

class CdIntegration extends ControllerBase {
    public function render() {
        $items = array(
            array('name' => 'Article oneoneone'),
            array('name' => 'Article two'),
            array('name' => 'Article three'),
            array('name' => 'Article four'),
        );
      
        return array(
            '#theme' => 'cd_integration_theme',
            '#items' => $items,
            '#title' => 'Campus Directory'
        );
    }
}