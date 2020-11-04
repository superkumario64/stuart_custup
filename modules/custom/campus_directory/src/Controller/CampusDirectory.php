<?php

namespace Drupal\campus_directory\Controller;

// do some https://api.drupal.org/api/drupal/core!lib!Drupal.php/function/Drupal%3A%3AentityQuery/8.2.x

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\campus_directory\Classes\CampusDirectory as CampusDirectoryClass;

class CampusDirectory extends ControllerBase {
    public function render($name) {

          $path = \Drupal::service('path.alias_manager')->getPathByAlias('/' . $name);
          if(preg_match('/node\/(\d+)/', $path, $matches)) {
            $node = \Drupal\node\Entity\Node::load($matches[1]);
          } else {
            echo "Directory not found";
            die;
          }

          $config = $this->config('campus_directory.settings');
          $ldap_password =  $config->get('lpad_password');

          $content = $node->toArray();
          $strCruzids = count($content["field_cruzids"]) ? $content["field_cruzids"][0]['value'] : "";

          return array(
            '#theme' => 'campus_directory',
            '#items' => CampusDirectoryClass::getCampusDirData($strCruzids, $ldap_password),
            '#title' => 'Campus Directory',
            '#param' => $name
          );
    }
}
