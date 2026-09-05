<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------------------------------------------------------------------------
// Public site
// ---------------------------------------------------------------------------
$routes->get('/', 'Home::index', ['as' => 'home']);

$routes->get('packages', 'Packages::index', ['as' => 'packages']);
// Serves the results grid on its own for htmx swaps.
$routes->get('packages/filter', 'Packages::filter', ['as' => 'packages-filter']);
// Search-as-you-type suggestions; must sit above the (:segment) catch-all.
$routes->get('packages/suggest', 'Packages::suggest', ['as' => 'packages-suggest']);
$routes->get('packages/(:segment)', 'Packages::show/$1', ['as' => 'package']);
$routes->post('packages/(:segment)/enquire', 'Packages::enquire/$1', ['as' => 'package-enquire']);

$routes->get('custom-trips', 'Pages::customTrips', ['as' => 'custom-trips']);
$routes->post('custom-trips', 'Pages::submitCustomTrip');

$routes->get('about', 'Pages::about', ['as' => 'about']);
$routes->get('gallery', 'Pages::gallery', ['as' => 'gallery']);
$routes->get('terms', 'Pages::terms', ['as' => 'terms']);
$routes->get('privacy', 'Pages::privacy', ['as' => 'privacy']);

$routes->get('contact', 'Pages::contact', ['as' => 'contact']);
$routes->post('contact', 'Pages::submitContact');

$routes->get('blog', 'Blog::index', ['as' => 'blog']);
$routes->get('blog/(:segment)', 'Blog::show/$1', ['as' => 'post']);

// Adventure tickets — reached by an unguessable token
$routes->get('ticket/(:segment)', 'Tickets::show/$1', ['as' => 'ticket']);

// SEO / AI crawler files (generated, so they track the live catalogue + base URL)
$routes->get('robots.txt', 'Seo::robots');
$routes->get('sitemap.xml', 'Seo::sitemap');
$routes->get('llms.txt', 'Seo::llms');

// ---------------------------------------------------------------------------
// Authentication (Shield). Registration and magic-link routes are removed in
// Config\Auth so only the login flow is reachable.
// ---------------------------------------------------------------------------
service('auth')->routes($routes, ['except' => ['register']]);

// ---------------------------------------------------------------------------
// Admin — everything behind Shield's session filter plus a group check
// ---------------------------------------------------------------------------
$routes->group('admin', [
    'filter'    => ['session', 'group:superadmin,admin'],
    'namespace' => 'App\Controllers\Admin',
], static function ($routes) {
    $routes->get('/', 'Dashboard::index', ['as' => 'admin']);

    $routes->get('homepage', 'Homepage::index', ['as' => 'admin-homepage']);
    $routes->post('homepage', 'Homepage::save');

    $routes->get('pages', 'Pages::index', ['as' => 'admin-pages']);
    $routes->post('pages', 'Pages::save');

    $routes->get('enquiries', 'Enquiries::index', ['as' => 'admin-enquiries']);
    $routes->get('enquiries/list', 'Enquiries::list');
    $routes->get('enquiries/(:num)', 'Enquiries::show/$1');
    $routes->post('enquiries/(:num)/status', 'Enquiries::status/$1');
    $routes->post('enquiries/(:num)/notes', 'Enquiries::notes/$1');
    $routes->post('enquiries/(:num)/delete', 'Enquiries::delete/$1');

    $routes->get('packages', 'Packages::index', ['as' => 'admin-packages']);
    $routes->get('packages/list', 'Packages::list');
    $routes->get('packages/new', 'Packages::new');
    $routes->post('packages', 'Packages::create');
    $routes->get('packages/(:num)/edit', 'Packages::edit/$1');
    $routes->post('packages/(:num)', 'Packages::update/$1');
    $routes->post('packages/(:num)/delete', 'Packages::delete/$1');
    $routes->post('packages/(:num)/toggle', 'Packages::toggle/$1');

    $routes->get('posts', 'Posts::index', ['as' => 'admin-posts']);
    $routes->get('posts/list', 'Posts::list');
    $routes->get('posts/new', 'Posts::new');
    $routes->post('posts', 'Posts::create');
    $routes->get('posts/(:num)/edit', 'Posts::edit/$1');
    $routes->post('posts/(:num)', 'Posts::update/$1');
    $routes->post('posts/(:num)/delete', 'Posts::delete/$1');

    $routes->get('gallery', 'Gallery::index', ['as' => 'admin-gallery']);
    $routes->post('gallery', 'Gallery::create');
    $routes->post('gallery/(:num)', 'Gallery::update/$1');
    $routes->post('gallery/(:num)/delete', 'Gallery::delete/$1');

    $routes->get('testimonials', 'Testimonials::index', ['as' => 'admin-testimonials']);
    $routes->post('testimonials', 'Testimonials::create');
    $routes->post('testimonials/(:num)', 'Testimonials::update/$1');
    $routes->post('testimonials/(:num)/delete', 'Testimonials::delete/$1');

    $routes->get('faqs', 'Faqs::index', ['as' => 'admin-faqs']);
    $routes->post('faqs', 'Faqs::create');
    $routes->post('faqs/(:num)', 'Faqs::update/$1');
    $routes->post('faqs/(:num)/delete', 'Faqs::delete/$1');

    // One controller drives all four lookup tables; {type} picks the model.
    $routes->get('taxonomy/(:segment)', 'Taxonomy::index/$1', ['as' => 'admin-taxonomy']);
    $routes->post('taxonomy/(:segment)', 'Taxonomy::create/$1');
    $routes->post('taxonomy/(:segment)/(:num)', 'Taxonomy::update/$1/$2');
    $routes->post('taxonomy/(:segment)/(:num)/delete', 'Taxonomy::delete/$1/$2');

    $routes->get('settings', 'Settings::index', ['as' => 'admin-settings']);
    $routes->post('settings', 'Settings::save');

    $routes->get('account', 'Account::index', ['as' => 'admin-account']);
    $routes->post('account/password', 'Account::changePassword');
});
