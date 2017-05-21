<?php

namespace Drupal\theme_field\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * Class theme_fieldThemeNegotiator.
 *
 * @package Drupal\theme_field
 */
class ThemeFieldNegotiator implements ThemeNegotiatorInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    // Retrieve an array which contains the path pieces.
    $current_path = \Drupal::service('path.current')->getPath();
    $path_args = explode('/', $current_path);
    $node = $route_match->getParameter('node');
    if (!empty($node) && empty($path_args[3])) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    $node = $route_match->getParameter('node');
    $theme_handler = \Drupal::service('theme_handler');
    // TODO: check if theme valid and still available.
    $theme_fields = \Drupal::entityManager()->getFieldMapByFieldType('theme_field_type');
    if (!empty($theme_fields)) {
      foreach ($theme_fields as $field_wrapper) {
        foreach (array_keys($field_wrapper) as $field_name) {
          if ($node->hasField($field_name) && $theme = $node->$field_name->getString()) {
            return $theme;
          }
        }
      }
    }
    return $theme_handler->getDefault();
  }

}
