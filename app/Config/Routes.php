<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('/ultimos_eventos', 'Eventos::ultimos');
$routes->get('/eventos', 'Eventos::lista');
$routes->get('/eventos/(:num)', 'Eventos::show/$1');
$routes->get('/inscribirse/(:num)/(:num)', 'Eventos::inscribirse/$1/$2');
$routes->get('/inscripcionManual/(:num)', 'Eventos::inscribirse/$1');
$routes->post('/inscripcion', 'Eventos::inscripcion');

$routes->get('/contactar', 'Contactar::index');
$routes->post('/contactar/enviar', 'Contactar::submit');

$routes->get('/pinturas', 'Galerias::lista');
$routes->get('/pinturas/(:num)', 'Galerias::show/$1');

/* -------------------------------- */

$routes->get('admin', 'Admin::index', ['filter' => 'auth']);
$routes->get('login', 'Admin::login');
$routes->get('logout', 'Admin::logout');
$routes->post('validar', 'Admin::validar');

$routes->get('migaleria', '\App\Controllers\Galeristas\AuthController::index', ['filter' => 'authGals']);
$routes->group('galeristas', ['namespace' => 'App\Controllers\Galeristas'], function ($routes) {
    $routes->get('', 'AuthController::index', ['filter' => 'authGals']);
    $routes->get('login', 'AuthController::login');
    $routes->post('validar', 'AuthController::validar');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('lista', 'ObrasController::lista', ['filter' => 'authGals']);
    $routes->get('cancelar', 'ObrasController::cancelar', ['filter' => 'authGals']);
    $routes->get('nuevo', 'ObrasController::nuevo', ['filter' => 'authGals']);
    $routes->post('crear', 'ObrasController::crear', ['filter' => 'authGals']);
    $routes->get('editar/(:num)', 'ObrasController::editar/$1', ['filter' => 'authGals']);
    $routes->put('modificar/(:num)', 'ObrasController::modificar/$1', ['filter' => 'authGals']);
    $routes->get('quitar/(:num)', 'ObrasController::quitar/$1', ['filter' => 'authGals']);
});

$routes->get('dashboard', 'Admin::dashboard', ['filter' => 'auth']);

$routes->group('control', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function ($routes) {

    $routes->get('usuarios', 'Usuarios::lista');
    $routes->get('usuarios/nuevo', 'Usuarios::new');
    $routes->post('usuarios/crear', 'Usuarios::create');
    $routes->get('usuarios/editar/(:num)', 'Usuarios::edit/$1');
    $routes->put('usuarios/modificar/(:num)', 'Usuarios::update/$1');
    $routes->delete('usuarios/(:num)', 'Usuarios::delete/$1');

    $routes->get('contactos', 'Contactos::lista');
    $routes->get('contactos/ajax', 'Contactos::getContactosAjax');
    $routes->get('contactos/nuevo', 'Contactos::new');
    $routes->post('contactos/crear', 'Contactos::create');
    $routes->get('contactos/editar/(:num)', 'Contactos::edit/$1');
    $routes->put('contactos/modificar/(:num)', 'Contactos::update/$1');
    $routes->delete('contactos/(:num)', 'Contactos::delete/$1');
    $routes->get('contactos/historia/(:num)', 'Contactos::historia/$1');

    $routes->get('sistema', 'Sistema::edit');
    $routes->put('sistema/modificar', 'Sistema::update');

    $routes->get('enlaces', 'Enlaces::lista');
    $routes->get('enlaces/nuevo', 'Enlaces::new');
    $routes->post('enlaces/crear', 'Enlaces::create');
    $routes->get('enlaces/editar/(:num)', 'Enlaces::edit/$1');
    $routes->put('enlaces/modificar/(:num)', 'Enlaces::update/$1');
    $routes->delete('enlaces/(:num)', 'Enlaces::delete/$1');

    $routes->get('correos', 'Correos::lista');
    $routes->get('correos/nuevo', 'Correos::new');
    $routes->post('correos/crear', 'Correos::create');
    $routes->get('correos/editar/(:num)', 'Correos::edit/$1');
    $routes->put('correos/modificar/(:num)', 'Correos::update/$1');
    $routes->delete('correos/(:num)', 'Correos::delete/$1');
    $routes->get('correos/cartear/(:num)', 'Correos::prepmail/$1');
    $routes->post('correos/enviomasivo/(:num)', 'Correos::sendmail/$1');
    $routes->post('correos/lote/(:num)', 'Correos::sendmailLote/$1');
    $routes->get('correos/resultado/(:num)', 'Correos::resultado/$1');
    $routes->get('correos/listado/(:num)', 'Correos::listado/$1');

    $routes->get('tipos', 'Tipos::lista');
    $routes->get('tipos/nuevo', 'Tipos::new');
    $routes->post('tipos/crear', 'Tipos::create');
    $routes->get('tipos/editar/(:num)', 'Tipos::edit/$1');
    $routes->put('tipos/modificar/(:num)', 'Tipos::update/$1');
    $routes->delete('tipos/(:num)', 'Tipos::delete/$1');

    $routes->get('eventos', 'Eventos::lista');
    $routes->get('eventos/nuevo', 'Eventos::new');
    $routes->post('eventos/crear', 'Eventos::create');
    $routes->get('eventos/editar/(:num)', 'Eventos::edit/$1');
    $routes->put('eventos/modificar/(:num)', 'Eventos::update/$1');
    $routes->delete('eventos/(:num)', 'Eventos::delete/$1');
    $routes->get('eventos/cartear/(:num)', 'Eventos::sendmail/$1');
    $routes->get('eventos/invitados/(:num)', 'Eventos::listaInvitados/$1');
    $routes->post('inscribirse/(:num)/(:num)', 'Eventos::inscribirInvitado/$1/$2');
    $routes->delete('quitarInvitado/(:num)', 'Eventos::quitarInvitado/$1');
    $routes->get('inscritos/(:num)', 'Eventos::listaInscritos/$1');
    $routes->get('inscripcionManual', 'Eventos::inscripcionManual');
    $routes->get('inscripcionManual/(:num)', 'Eventos::inscripcionManual/$1');
    $routes->post('inscripcionManual', 'Eventos::grabarInscripcionManual');
    $routes->delete('quitarInscrito/(:num)', 'Eventos::quitarInscrito/$1');
    $routes->get('enEspera/(:num)', 'Eventos::listaDeEspera/$1');
    $routes->post('inscribirDeEspera/(:num)', 'Eventos::inscribirDeEspera/$1');
    $routes->delete('quitarDeEspera/(:num)', 'Eventos::quitarDeEspera/$1');
    $routes->post('contactoDesdeInscrito', 'Eventos::contactoDesdeInscrito');
    $routes->get('completaContactoDesdeInscrito/(:num)', 'Eventos::completaContactoDesdeInscrito/$1');


    $routes->get('fotos/(:num)', 'Eventos::fotos/$1');
    $routes->delete('fotos/(:num)/(:segment)', 'Eventos::eliminarFoto/$1/$2');
    $routes->post('fotos/(:num)', 'Eventos::agregarFotos/$1');

    $routes->get('listarInscritos/(:num)', 'Pdf::listarInscritos/$1');
    $routes->get('listarInvitados/(:num)', 'Pdf::listarInvitados/$1');
    $routes->get('listarEnEspera/(:num)', 'Pdf::listarEnEspera/$1');

    $routes->get('emailsIns', 'EmailsIns::lista');
    $routes->post('emailsIns/(:num)', 'EmailsIns::inscribe/$1');
    $routes->delete('emailsIns/(:num)', 'EmailsIns::delete/$1');

    $routes->get('(:any)', 'Admin::index', ['filter' => 'auth']);
});

$routes->group('bajasxpiecorreo', ['namespace' => 'App\Controllers\Admin'], function ($routes) {

    $routes->get('emails/(:num)/(:segment)', 'Autobajas::emails/$1/$2');
    $routes->get('invitaciones/(:num)/(:segment)', 'Autobajas::invitaciones/$1/$2');
    $routes->get('total/(:num)/(:segment)', 'Autobajas::bajaTotal/$1/$2');
});


