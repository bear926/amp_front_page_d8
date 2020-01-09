<?php

namespace Drupal\amp_front_page;

use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\Routing\Route;
use Drupal\Core\Path\PathMatcherInterface;

/**
 * Provides check is page disable in to amp_front_page.
 */
class PageCheck {

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $currentRouteMatch;

  /**
   * The path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  protected $pathMatches;

  /**
   * The amp config.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $ampConfig;

  /**
   * Construct a new AmpFrontPageCheck.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   * @param \Drupal\Core\Path\PathMatcherInterface $path_matcher
   *   The path matcher.
   *
   */
  public function __construct(RouteMatchInterface $route_match, PathMatcherInterface $path_matcher) {
    $this->currentRouteMatch = $route_match;
    $this->pathMatches = $path_matcher;
    $this->ampConfig = \Drupal::config('amp.settings');
  }

  /**
   * Check if current page disable for amp mode.
   *
   * @param string $address_str
   *   String with page address.
   * @param \Symfony\Component\Routing\Route|NULL $route
   *   Set route if you dont need current page.
   *
   * @return bool
   */
  public function check($address_str, Route $route = NULL) {
    $route = ($route) ? $route : $this->getCurrentRouteMatch()->getRouteObject();
    $path_matches = $this->getPathMatches();
    $amp_config = $this->getAmpConfig();
    $amp_front_page_pattern = $amp_config->get('amp_front_page_pattern');
    $amp_front_page_pattern_disable = $amp_config->get('amp_front_page_pattern_disable');
    $is_valid_pages = explode(', ', $amp_front_page_pattern);
    $is_disable_pages = explode(', ', $amp_front_page_pattern_disable);
    $patch = $route->getPath();

    // Check disable page.
    foreach ($is_disable_pages as $pattern) {
      if ($path_matches->matchPath($patch, $pattern) || $path_matches->matchPath($address_str, $pattern)) {
        return FALSE;
      }
    }

    // Check valid page.
    foreach($is_valid_pages as $pattern) {
      if ($path_matches->matchPath($patch, $pattern) || $path_matches->matchPath($address_str, $pattern)) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentRouteMatch() {
    return $this->currentRouteMatch;
  }

  /**
   * {@inheritdoc}
   */
  public function getPathMatches() {
    return $this->pathMatches;
  }

  /**
   * {@inheritdoc}
   */
  public function getAmpConfig() {
    return $this->ampConfig;
  }

}
