<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulos extends CI_Controller {

  public function __construct() {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function index() {
      redirect('/frontend/portada/');
  }

  public function masArticulos($limit = NULL) {
      if($limit === NULL) $limit = 10;
      $articulos_viejos = array();

      if($this->input->post('articulos_viejos') !== NULL) {
          $articulos_viejos = $this->input->post('articulos_viejos');
      }

      $res = array();

      $distancia = 0;
      $latitud = 40.4168;
      $longitud = -3.7038;
      $order = '';

      if($this->input->get('nombre') !== NULL || $this->input->get('tags') !== NULL) {
          if($this->input->get('order_distancia') !== NULL) {
              $distancia = $this->input->get('order_distancia');

              if( ! is_numeric($distancia) || $distancia < 0) {
                  $distancia = 0;
              } else if ($this->input->get('latitud') !== NULL &&
                         $this->input->get('longitud') !== NULL) {

                  $latitud = $this->input->get('latitud');
                  $longitud = $this->input->get('longitud');

                  if( ! is_numeric($latitud)) {
                     $latitud = 40.4168;
                  }

                  if( ! is_numeric($longitud)) {
                     $longitud = -3.7038;
                  }
              }
          }

          if($this->input->get('order') !== NULL) {
              $order = $this->input->get('order');

              switch ($order) {
                  case 'precio_asc':
                      break;
                  case 'precio_desc':
                      break;
                  case 'prox':
                      break;
                  default:
                        $order = '';
                      break;
              }
          }

          $etiquetas = preg_split('/,/', $this->input->get('tags'));
          if($etiquetas[0] === '') {
              $etiquetas = array();
          }
          $nombre = $this->input->get("nombre");
          $res =
               $this->Articulo->busqueda_articulo($limit, $etiquetas,
                                                  $nombre,
                                                  $order,
                                                  $distancia,
                                                  $latitud,
                                                  $longitud,
                                                  $articulos_viejos);
      } else {
          if($this->Usuario->logueado()):
              $usuario = $this->session->userdata("usuario");
              $res =
                  $this->Articulo->todos_sin_favorito($usuario['id'], $limit,
                                                      "now()", $order,
                                                      $distancia, $latitud,
                                                      $longitud, $articulos_viejos);
          else:
              $res = $this->Articulo->todos($limit, "now()",
                                            $order, $distancia,
                                            $latitud, $longitud,
                                            $articulos_viejos);
          endif;
      }

      echo json_encode($res);
  }

  public function buscar($id_articulo = NULL) {
      if($id_articulo === NULL || $this->Articulo->por_id($id_articulo) === FALSE) {
          $mensajes[] = array('error' =>
              "Parametros incorrectos para visualizar el articulo en vista detallada.");
          $this->flashdata->load($mensajes);

          redirect('/frontend/portada/');
      }

      $data['articulo'] = $this->Articulo->por_id($id_articulo);
      $id_usuario = $data['articulo']['usuario_id'];

      $data['usuario'] = $this->Usuario->por_id($id_usuario);
      $data['articulos_usuarios'] = $this->Articulo->por_id_vista($id_usuario, $id_articulo);

      $this->template->load("/articulos/buscar", $data);
  }

  public function eliminar_favorito($articulo_id = NULL) {
      if (!$this->Usuario->logueado() || $articulo_id === NULL) {
          $mensajes[] = array('error' =>
                  "Parametros incorrectos para añadir/eliminar a favoritos el articulo.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }
      $usuario = $this->session->userdata('usuario');
      $this->Articulo->borrar_favorito($usuario['id'], $articulo_id);

      redirect('/usuarios/perfil/' . $usuario['id']);
  }

  public function favoritos($articulo_id) {
      if (!$this->Usuario->logueado() ||
           $articulo_id === NULL ||
           $this->Articulo->por_id($articulo_id) === FALSE) {

          echo "articulo no valido";
      } else {
          $usuario = $this->session->userdata('usuario');
          if($this->Articulo->existe_favorito($usuario['id'], $articulo_id)) {
              $this->Articulo->borrar_favorito($usuario['id'], $articulo_id);
          } else {
              $this->Articulo->insertar_favorito($usuario['id'], $articulo_id);
          }
      }
  }

  public function editar_articulo($articulo_id = NULL) {
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes editar un articulo si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }
      $usuario = $this->session->userdata('usuario');
      if($this->Articulo->por_id_editar($usuario['id'], $articulo_id) === FALSE) {
          $mensajes[] = array('error' =>
                  "No puedes editar un articulo que no es tuyo.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }

      if ($this->input->post('editar') !== NULL) {
          $reglas = array(
              array(
                  'field' => 'nombre',
                  'label' => 'Nombre',
                  'rules' => 'trim|required|max_length[100]',
              ),
              array(
                  'field' => 'descripcion',
                  'label' => 'Descripción',
                  'rules' => 'trim|required|max_length[500]',
              ),
              array(
                  'field' => 'tags',
                  'label' => 'Etiquetas',
                  'rules' => 'trim|required',
              ),
          );
          $this->form_validation->set_rules($reglas);
          if ($this->form_validation->run() === TRUE) {
              $articulo = $this->input->post();

              $articulo['precio'] = (double) $articulo['precio'];
              $etiquetas = $articulo['tags'];
              $etiquetas = preg_split('/,/', $etiquetas);

              unset($articulo['editar']);
              unset($articulo['tags']);

              $articulo['usuario_id'] = $this->session->userdata('usuario')['id'];
              $this->Articulo->editar($articulo, $articulo_id);
              $this->Etiqueta->borrar_etiquetas_articulo($articulo_id);

              foreach($etiquetas as $v) {
                  $etiqueta = $this->Etiqueta->existe_etiqueta($v);
                  if(!$etiqueta) {
                      $etiqueta = $this->Etiqueta->insertar($v);
                  }

                  $this->Etiqueta->insertar_etiqueta_articulo(array(
                      'etiqueta_id' => $etiqueta['id'],
                      'articulo_id' => $articulo_id
                  ));
              }

              redirect('/frontend/portada');
          }
      }
      $data['articulo'] = $this->Articulo->por_id($articulo_id);
      $data['articulo']['precio'] = str_replace('.', '', $data['articulo']['precio']);
      $data['articulo']['precio'] = str_replace(',', '.', $data['articulo']['precio']);
      $data['articulo']['precio'] = substr($data['articulo']['precio'], 0, -4);

      $this->breadcrumbcomponent->add('Home', base_url());
      $this->breadcrumbcomponent->add('Perfil',
                                      base_url() . 'usuarios/perfil/'.$data['articulo']['usuario_id']);
      $this->breadcrumbcomponent->add('Editar Articulo',
                                      base_url() . 'articulos/editar_articulo/'.$data['articulo']['id']);
      $this->breadcrumbcomponent->add($data['articulo']['nombre'], base_url());

      $this->template->load('/articulos/editar_articulo', $data);
  }

  public function subir() {
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes insertar articulos si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }

      if ($this->input->post('subir') !== NULL) {
          $reglas = array(
              array(
                  'field' => 'nombre',
                  'label' => 'Nombre',
                  'rules' => 'trim|required|max_length[100]',
              ),
              array(
                  'field' => 'descripcion',
                  'label' => 'Descripción',
                  'rules' => 'trim|required|max_length[500]',
              ),
              array(
                  'field' => 'tags',
                  'label' => 'Etiquetas',
                  'rules' => 'trim|required',
              ),
          );
          $this->form_validation->set_rules($reglas);
          if ($this->form_validation->run() === TRUE) {
              $articulo = $this->input->post();

              $articulo['precio'] = (double) $articulo['precio'];
              $etiquetas = $articulo['tags'];
              $etiquetas = preg_split('/,/', $etiquetas);

              unset($articulo['subir']);
              unset($articulo['tags']);

              $articulo['usuario_id'] = $this->session->userdata('usuario')['id'];
              $articulo_insertado = $this->Articulo->insertar($articulo);
              $articulo_id = $articulo_insertado['id'];

              foreach($etiquetas as $v) {
                  $etiqueta = $this->Etiqueta->existe_etiqueta($v);
                  if(!$etiqueta) {
                      $etiqueta = $this->Etiqueta->insertar($v);
                  }

                  $this->Etiqueta->insertar_etiqueta_articulo(array(
                      'etiqueta_id' => $etiqueta['id'],
                      'articulo_id' => $articulo_id
                  ));
              }

              $sesion = $this->session->userdata('usuario');
              $sesion['ultimo_articulo'] = $articulo_id;
              $this->session->set_userdata('usuario', $sesion);

              redirect('/articulos/subir_imagenes');
          }
      }

      $this->breadcrumbcomponent->add('Home', base_url());
      $this->breadcrumbcomponent->add('Subir Articulo',
                                      base_url() . 'articulos/subir/');

      $this->template->load('/articulos/subir');
  }

  public function subir_imagenes() {
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes insertar articulos si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }

      if($this->input->post('subir') !== NULL) {
          $sesion = $this->session->userdata('usuario');
          unset($sesion['ultimo_articulo']);
          $this->session->set_userdata('usuario', $sesion);
      }
      if(isset($this->session->userdata('usuario')['ultimo_articulo'])) {
          $articulo_id = $this->session->userdata('usuario')['ultimo_articulo'];

          $data['error'] = array();

          $config['upload_path'] = 'imagenes_articulos/';
          $config['allowed_types'] = 'jpeg|jpg|jpe';
          $config['overwrite'] = TRUE;
          $config['max_width'] = '5000';
          $config['max_height'] = '5000';
          $config['max_size'] = '500';

          for ($i = 1; $i <= 4; $i++) {
              if(!is_file($_SERVER["DOCUMENT_ROOT"] .
                          '/imagenes_articulos/' .
                          $articulo_id . '_' . $i .
                          '.jpg')):

                $config['file_name'] = $articulo_id . '_' . $i . '.jpg';
                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('foto')) {
                  $data['error'] = $this->upload->display_errors();
                }
                else {
                  $data = array('upload_data' => $this->upload->data());
                }
                break;
              endif;
          }

          $articulo = $this->Articulo->por_id($articulo_id);
          $this->breadcrumbcomponent->add('Home', base_url());
          $this->breadcrumbcomponent->add('Subir Imágenes',
                                          base_url() . 'articulos/subir_imagenes/');
          $this->breadcrumbcomponent->add($articulo['nombre'], base_url());

          $this->template->load('/articulos/subir_imagenes');
      } else {
          redirect('/frontend/portada');
      }
  }

  public function borrar_imagen($numero = NULL) {
      if($numero !== NULL && $this->Usuario->logueado()) {
          $articulo_id = $this->session->userdata('usuario')['ultimo_articulo'];
          unlink($_SERVER["DOCUMENT_ROOT"] .
                      '/imagenes_articulos/' .
                      $articulo_id . '_' . $numero .
                      '.jpg');
      }
  }

  public function vender($articulo_id = NULL) {
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes vender articulos si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }
      $usuario_id = $this->session->userdata('usuario')['id'];
      if(!$this->Articulo->es_propietario($usuario_id, $articulo_id)) {
          redirect('/frontend/portada/');
      }
      else if($this->Articulo->articulo_vendido($articulo_id)) {
          redirect('/frontend/portada/');
      }

      if ($this->input->post('vender') !== NULL) {
          $reglas = array(
              array(
                  'field' => 'nick_comprador',
                  'label' => 'Nick comprador',
                  'rules' => array(
                      'trim', 'required',
                      array('existe_nick', array($this->Usuario, 'existe_nick')),
                      array('existe_nick_registrado', array($this->Usuario, 'existe_nick_registrado'))
                  ),
                  'errors' => array(
                      'existe_nick' => 'El nick debe ser de un usuario valido.',
                      'existe_nick_registrado' => 'El nick debe ser de un usuario valido.'
                  ),
              ),
          );

          $venta = $this->input->post();
          unset($venta['venta']);
          $valores = array(
              'vendedor_id' => $usuario_id,
              'comprador_id' => NULL,
              'articulo_id' => $articulo_id,
          );

          if(isset($venta['nick_comprador']) && $venta['nick_comprador'] !== NULL) {
              if(isset($venta['valoracion']) && $venta['valoracion'] !== NULL &&
                 isset($venta['valoracion_text']) && $venta['valoracion_text'] !== NULL) {
                  $reglas[1] = array(
                      'field' => 'valoracion',
                      'label' => 'Valoracion',
                      'rules' => array(
                          'trim', 'required',
                          array('valoracion_correcta', function ($valoracion) {
                                  return ((int) $valoracion >= 0 && (int) $valoracion <= 5);
                              }),
                      ),
                      'errors' => array(
                          'valoracion_correcta' => 'La valoracion debe ser un numero comprendido entre 0 y 5.',
                      ),
                  );
                  $reglas[2] = array(
                      'field' => 'valoracion_text',
                      'label' => 'Valoracion Texto',
                      'rules' => array(
                          'trim',
                          array('valoracion_texto_size', function ($valoracion_texto) {
                                  return !(strlen($valoracion_texto) > 200);
                              }),
                      ),
                      'errors' => array(
                          'valoracion_texto_size' => "La valoracion en texto no debe superar los 200 caracteres."
                      ),
                  );
              }
              $this->form_validation->set_rules($reglas);
              if ($this->form_validation->run() === TRUE) {
                  $comprador = $this->Usuario->por_nick($venta['nick_comprador']);
                  $valores['comprador_id'] = $comprador['id'];
                  $venta_realizada = $this->Articulo->vender($valores);

                  $usuarios_favoritos = $this->Articulo->articulo_favorito_email($articulo_id);
                  $articulo = $this->Articulo->por_id($articulo_id);
                  if($usuarios_favoritos !== FALSE) {
                      foreach ($usuarios_favoritos as $user) {
                          $enlace = anchor('/articulos/buscar/'.$articulo_id, $articulo['nombre']);
                          $message = "Le comunicamos que el articulo " . $enlace .
                                     " que usted tiene en su lista de favoritos ha sido vendido.";
                          $this->load->library('email');
                          $this->email->from('jdkdejava@gmail.com');
                          $this->email->to($user['email']);
                          $this->email->subject('Articulo Vendido en BuyAndSell');
                          $this->email->message($message);
                          $this->email->send();
                      }
                  }
                  if(isset($venta['valoracion']) && $venta['valoracion'] !== NULL &&
                     isset($venta['valoracion_text']) && $venta['valoracion_text'] !== NULL) {
                      $valoracion = array(
                          'venta_id' => $venta_realizada['id'],
                          'valoracion' => $venta['valoracion'],
                          'valoracion_text' => $venta['valoracion_text'],
                      );
                      $this->Valoracion->insertar_valoracion_vendedor($valoracion);
                  }
                  redirect('/frontend/portada/');
              }
          } else {
              $this->Articulo->vender($valores);
              redirect('/frontend/portada/');
          }
      }


      $data['articulo'] = $this->Articulo->por_id($articulo_id);
      $data['usuario'] = $this->Usuario->por_id($usuario_id);

      $data['opciones_venta'] = array(
          0 => "Venta normal",
          1 => "Venta con valoracion",
          2 => "Venta sin usuario",
      );

      $this->breadcrumbcomponent->add('Home', base_url());
      $this->breadcrumbcomponent->add('Perfil',
                                      base_url() . 'usuarios/perfil/'.$data['articulo']['usuario_id']);
      $this->breadcrumbcomponent->add('Vender',
                                      base_url() . 'articulos/vender/'.$data['articulo']['id']);
      $this->breadcrumbcomponent->add($data['articulo']['nombre'], base_url());

      $this->template->load('/articulos/vender', $data);
  }

  public function retirar_articulo($articulo_id = NULL) {
      $usuario = $this->session->userdata('usuario');
      if (!$this->Usuario->logueado() || !$usuario['admin']) {
          redirect('/frontend/portada/');
      }
      $articulo = $this->Articulo->por_id($articulo_id);
      if($articulo === FALSE) {
          redirect('/frontend/portada/');
      }

      $this->Articulo->retirar_articulo($articulo_id, $articulo['usuario_id']);

      if (isset($_SERVER['HTTP_REFERER']) && !$this->session->has_userdata('last_uri')) {
          $this->session->set_userdata('last_uri',
                          parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
      }
      if($this->session->has_userdata('last_uri')) {
          $uri = $this->session->userdata('last_uri');
          $this->session->unset_userdata('last_uri');
          redirect($uri);
      }
  }

  public function borrar($articulo_id = NULL) {
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes borrar articulos si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }
      $usuario = $this->session->userdata('usuario');
      if(!$usuario['admin']) {
          if(!$this->Articulo->es_propietario($usuario['id'], $articulo_id))  {
              redirect('/frontend/portada/');
          }
      }

      $this->Articulo->borrar($articulo_id);

      for ($i = 1; $i <= 4; $i++) {
          unlink($_SERVER["DOCUMENT_ROOT"] .
                      '/imagenes_articulos/' .
                      $articulo_id . '_' . $i .
                      '.jpg');
      }

      if (isset($_SERVER['HTTP_REFERER']) && !$this->session->has_userdata('last_uri')) {
          $this->session->set_userdata('last_uri',
                          parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
      }

      if($this->session->has_userdata('last_uri')) {
          $uri = $this->session->userdata('last_uri');
          $this->session->unset_userdata('last_uri');
          redirect($uri);
      }
  }
  public function existe_imagen($nombre = NULL) {
      $existe = FALSE;
      if($nombre !== NULL) {
          if(is_file($_SERVER["DOCUMENT_ROOT"] . '/imagenes_articulos/' . $nombre)):
              $existe = TRUE;
          endif;
      }

      echo json_encode(array(
          'existe' => $existe
      ));
  }
}
