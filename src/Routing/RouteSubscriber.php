<?php
namespace Drupal\amp_front_page\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('amp.settings')) {
      $route->setDefault('_form', 'Drupal\amp_front_page\Form\AmpFrontPageSettingsForm');
    }
  }

}
