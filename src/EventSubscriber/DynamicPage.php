<?php

namespace Drupal\amp_front_page\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Subscriber that wraps controllers, to handle early rendering.
 */
class DynamicPage implements EventSubscriberInterface {

  /**
   * Subscribe for enable amp page.
   */
  public function onController(GetResponseEvent $event) {
    $request = $event->getRequest();

    $route = $request->get('_route_object');
    $checker = \Drupal::service('amp_front_page.page_check');
    if (!$checker->check($request->getPathInfo(), $route)) {
      $route->setOption('_amp_route', FALSE);
      unset($_GET['amp']);
    }
    elseif(isset($_GET['amp'])) {
      $route->setOption('_amp_route', TRUE);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onController', 28];

    return $events;
  }

}