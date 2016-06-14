<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller{

    private $reglas_comunes = array(
        array(
            'field' => 'nick',
            'label' => 'Nick',
            'rules' => 'trim|required|max_length[15]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'password',
            'label' => 'Contraseña',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'password_confirm',
            'label' => 'Confirmar contraseña',
            'rules' => 'trim|required|matches[password]'
        )
    );

    public function __construct() {
        parent::__construct();
    }

  /////////////////////////////////////////////////////////////////////////////////////////////////
  public function _password_valido($password, $nick) {
      $usuario = $this->Usuario->por_nick($nick);

      if ($usuario !== FALSE &&
          password_verify($password, $usuario['password']) === TRUE) {
          return TRUE;
      }
      else {
          $this->form_validation->set_message('_password_valido',
              'La {field} no es válida.');
          return FALSE;
      }
  }

  private function limpiar($accion, $valores) {
      unset($valores[$accion]);
      $valores['password'] = password_hash($valores['password'], PASSWORD_DEFAULT);
      unset($valores['password_confirm']);

      return $valores;
  }

  public function _password_anterior_correcto($password_anterior, $id) {
      $valores = $this->Usuario->password($id);
      if (password_verify($password_anterior, $valores['password']) === TRUE)
      {
          return TRUE;
      }
      else
      {
          $this->form_validation->set_message('_password_anterior_correcto',
              'La {field} no es correcta.');
          return FALSE;
      }
  }

  public function _nick_unico($nick, $id) {
      $res = $this->Usuario->por_nick($nick);

      if ($res === FALSE || $res['id'] === $id)
      {
          return TRUE;
      }
      else
      {
          $this->form_validation->set_message('_nick_unico',
              'El {field} debe ser único.');
          return FALSE;
      }
  }

  public function login() {
    if ($this->Usuario->logueado()) {
        redirect('/frontend/portada/');
    }

    if ($this->input->post('login') !== NULL) {
        $nick = $this->input->post('nick');

        $reglas = array(
            array(
                'field' => 'nick',
                'label' => 'Nick',
                'rules' => array(
                    'trim', 'required',
                    array('existe_nick', array($this->Usuario, 'existe_nick')),
                    array('existe_nick_registrado', array($this->Usuario, 'existe_nick_registrado')),
                    array('usuario_baneado', array($this->Usuario, 'usuario_baneado_nick')),
                ),
                'errors' => array(
                    'existe_nick' => 'El nick debe existir.',
                    'existe_nick_registrado' => 'Esta cuenta todavia no ha sido validada por' .
                                                ' los medios correspondientes. Por favor, ' .
                                                'valide su cuenta.',
                    'usuario_baneado' => 'Tu usuario a sido baneado de este sitio web.'
                ),
            ),
            array(
                'field' => 'password',
                'label' => 'Contraseña',
                'rules' => "trim|required|callback__password_valido[$nick]"
            )
        );

        $this->form_validation->set_rules($reglas);
        if ($this->form_validation->run() === TRUE) {
            $usuario = $this->Usuario->por_nick($nick);

            $this->session->set_userdata('usuario', array(
                'id' => $usuario['id'],
                'nick' => $nick,
                'admin' => $this->Usuario->es_admin($nick),
            ));

            if($this->session->has_userdata('last_uri')) {
                $uri = $this->session->userdata('last_uri');
                $this->session->unset_userdata('last_uri');
                redirect($uri);
            }
            else {
                redirect('/frontend/portada/');
            }
        }
    }

    if (isset($_SERVER['HTTP_REFERER']) && !$this->session->has_userdata('last_uri')) {
        $this->session->set_userdata('last_uri',
                        parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
    }
    $this->breadcrumbcomponent->add('Home', base_url());
    $this->breadcrumbcomponent->add('Login',
                                    base_url() . 'usuarios/login/');

    $this->output->delete_cache('/frontend/portada/');
    $this->template->load('/usuarios/login');
  }

  public function logout() {
    $this->output->delete_cache('/frontend/portada/');
    $this->session->sess_destroy();
    redirect('/frontend/portada/');
  }

  function index() {
      redirect('/frontend/portada/');
  }

  public function foto($id = NULL) {
        if($id === NULL) {
            $mensajes[] = array('error' =>
                    "Parámetros incorrectos para acceder a subida de foto de perfil, por favor, intentelo de nuevo.");
            $this->flashdata->load($mensajes);
            redirect('usuarios/login');
        }
        $data['id'] = $id;
        $data['error'] = array();

        if ($this->input->post('insertar') !== NULL) {
            $config['upload_path'] = 'images/usuarios/';
            $config['allowed_types'] = 'jpeg|jpg|jpe';
            $config['overwrite'] = TRUE;
            $config['max_width'] = '250';
            $config['max_height'] = '250';
            $config['max_size'] = '100';
            $config['file_name'] = $id . '.jpeg';

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('foto')) {
                $data['error'] = $this->upload->display_errors();
            }
            else {
                $data = array('upload_data' => $this->upload->data());

                $imagen = new Imagick($data['upload_data']['full_path']);
                $imagen->adaptiveResizeImage(70, 70);
                $imagen->writeImageFile(fopen("images/usuarios/" . $id . "_thumbnail.jpeg", "w"));

                redirect('usuarios/perfil/' . $id);
            }
        }
        $this->template->load('usuarios/foto', $data);
    }

    public function validar($usuario_id = NULL, $token = NULL) {
        if($usuario_id === NULL || $token === NULL) {
            $mensajes[] = array('error' =>
                "Parametros incorrectos para la validación de la cuenta.");
            $this->flashdata->load($mensajes);

            redirect('/usuarios/login/');
        }

        $usuario_id = trim($usuario_id);
        $token = trim($token);
        $this->load->model('Token');
        $res = $this->Token->comprobar($usuario_id, $token);

        if ($res === FALSE) {
            $mensajes[] = array('error' =>
                "Parametros incorrectos para la validación de la cuenta.");
            $this->flashdata->load($mensajes);

            redirect('/usuarios/login/');
        }

        ######################################################

        $valores = array(
            'registro_verificado' => TRUE
        );

        $this->Usuario->editar($valores, $usuario_id);
        $this->Token->borrar($usuario_id);

        $mensajes[] = array('info' =>
            "Cuenta validada. Ya puede logear en el sistema.");
        $this->flashdata->load($mensajes);

        redirect('/usuarios/login/');
    }

    public function registrar() {
        if ($this->Usuario->logueado()) {
            redirect('/frontend/portada/');
        }

        if ($this->input->post('registrar') !== NULL) {
            $reglas = $this->reglas_comunes;
            $reglas[0] = array(
                            'field' => 'nick',
                            'label' => 'Nick',
                            'rules' => array(
                                'trim', 'required', 'max_length[100]',
                                array('existe_nick', function ($nick) {
                                        return !$this->Usuario->existe_nick($nick);
                                    }
                                )
                            ),
                            'errors' => array(
                                'existe_nick' => 'El nick ya existe, por favor, escoja otro.',
                            )
                        );
            $reglas[1] = array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => array(
                    'trim', 'required', 'max_length[100]',
                    array('existe_email', function ($email) {
                            return !$this->Usuario->existe_email($email);
                        }
                    ),
                    array('dominio_email', function ($email) {
                            return $this->domain_exists($email);
                        }
                    ),
                ),
                'errors' => array(
                    'existe_email' => 'El email ya existe, por favor, escoja otro.',
                    'dominio_email' => 'Este email no puede existir, por favor, vuelva a intertarlo.'
                )
            );

            $this->form_validation->set_rules($reglas);
            if ($this->form_validation->run() === TRUE) {

                $valores = $this->input->post();

                unset($valores['registrar']);
                unset($valores['password_confirm']);
                unset($valores['geolocalizacion']);
                unset($valores['localizacion']);

                $valores['password'] = password_hash($valores['password'], PASSWORD_DEFAULT);
                $valores['registro_verificado'] = FALSE;
                $valores['ip'] = $this->blacklist->get_real_ip();
                $valores['email_favorito'] = FALSE;
                $valores['latitud'] = ($valores['latitud'] !== 'null') ? (double) $valores['latitud'] : NULL;
                $valores['longitud'] = ($valores['longitud'] !== 'null') ? (double) $valores['longitud'] : NULL;
                $valores['nick'] = strtolower($valores['nick']);

                $this->Usuario->insertar($valores);

                $this->load->model('Token');
                # Prepara correo
                $usuario = $this->Usuario->por_nick($valores['nick']);
                $usuario_id = $usuario['id'];

                ################################################################

                # Mandar correo
                $enlace = anchor('/usuarios/validar/' . $usuario_id . '/' .
                                 $this->Token->generar($usuario_id));

                $this->load->library('email');
                $this->email->from('jdkdejava@gmail.com');
                $this->email->to($valores['email']);
                $this->email->subject('Confirmar Registro');
                $this->email->message($enlace);
                $this->email->send();

                ################################################################

                $mensajes[] = array('info' =>
                        "Confirme su cuenta a traves de su correo electrónico.");

                $this->flashdata->load($mensajes);

                redirect('/usuarios/login');
            }
        }

        $this->breadcrumbcomponent->add('Home', base_url());
        $this->breadcrumbcomponent->add('Registrar',
                                        base_url() . 'usuarios/registrar/');
        $this->template->load('/usuarios/registrar');
    }

    public function recordar() {
        if ($this->input->post('recordar') !== NULL) {
            $reglas = array(
                array(
                    'field' => 'nick',
                    'label' => 'Nick',
                    'rules' => array(
                        'trim',
                        'required',
                        array('existe_usuario', array($this->Usuario, 'existe_nick')
                        )
                    ),
                    'errors' => array(
                        'existe_usuario' => 'Ese usuario no existe.'
                    )
                )
            );
            $this->form_validation->set_rules($reglas);
            if ($this->form_validation->run() !== FALSE) {
                # Preparar correo

                $nick = $this->input->post('nick');
                $usuario = $this->Usuario->por_nick($nick);
                $usuario_id = $usuario['id'];
                $email = $usuario['email'];

                $this->load->model('Token');
                $enlace = anchor('/usuarios/regenerar/' . $usuario_id . '/' .
                                 $this->Token->generar($usuario_id));

                # Mandar correo

                $this->load->library('email');
                $this->email->from('jdkdejava@gmail.com');
                $this->email->to($email);
                $this->email->subject('Regenerar Contraseña');
                $this->email->message($enlace);
                $this->email->send();

                ################################################################

                $mensajes[] = array('info' =>
                    "Se ha enviado un correo a su dirección de email.");
                $this->flashdata->load($mensajes);

                redirect('/usuarios/login');
            }
        }

        $this->template->load('/usuarios/recordar');
    }

    public function regenerar($usuario_id = NULL, $token = NULL) {
        if($usuario_id === NULL || $token === NULL) {
            $mensajes[] = array('error' =>
                "Párametros incorrectos para la regeneración de contraseña.");
            $this->flashdata->load($mensajes);

            redirect('/usuarios/login/');
        }

        $usuario_id = trim($usuario_id);
        $token = trim($token);
        $this->load->model('Token');
        $res = $this->Token->comprobar($usuario_id, $token);

        if ($res === FALSE) {
            $mensajes[] = array('error' =>
                "Párametros incorrectos para la regeneración de contraseña.");
            $this->flashdata->load($mensajes);

            redirect('/usuarios/login/');
        }

        ######################################################

        if ($this->input->post('regenerar') !== NULL) {
            $reglas = array(
                $this->reglas_comunes[2], $this->reglas_comunes[3]
            );

            $this->form_validation->set_rules($reglas);
            if ($this->form_validation->run() !== FALSE) {
                $password = $this->input->post('password');
                $nueva_password = password_hash($password, PASSWORD_DEFAULT);
                $this->Usuario->actualizar_password($usuario_id, $nueva_password);
                $this->Token->borrar($usuario_id);

                $mensajes[] = array('info' =>
                    "Su contraseña se ha regenerado correctamente");
                $this->flashdata->load($mensajes);

                redirect('/usuarios/login/');
            }
        }

        ########################################################

        $data = array(
            'usuario_id' => $usuario_id,
            'token' => $token
        );
        $this->template->load('/usuarios/regenerar/', $data);
    }

    public function perfil($usuario_id = NULL) {
        if($usuario_id === NULL || $this->Usuario->por_id($usuario_id) === FALSE) {
            $mensajes[] = array('error' =>
                "Parametros incorrectos para visualizar el perfil del usuario.");
            $this->flashdata->load($mensajes);

            redirect('/frontend/portada/');
        }
        $data['usuario'] = $this->Usuario->por_id($usuario_id);
        $data['articulos_usuarios'] = $this->Usuario->por_id_vista($usuario_id);
        $data['articulos_vendidos'] = $this->Usuario->ventas_usuario($usuario_id);
        $data['articulos_favoritos'] = array();
        $data['pm'] = array();
        $data['usuario_perfil'] = FALSE;
        $data['usuario_propio'] = $this->session->userdata('usuario');

        $data['articulos_comprados'] = $this->Usuario->compras_usuario($usuario_id);
        $data['valoraciones_compras'] = $this->Usuario->valoraciones_a_comprador($usuario_id);
        $data['valoraciones_ventas'] = $this->Usuario->valoraciones_a_vendedor($usuario_id);

        if ($this->Usuario->logueado()) {
            if ($data['usuario_propio']['id'] === $usuario_id && !$data['usuario_propio']['admin']) {
                $data['articulos_favoritos'] = $this->Articulo->articulos_favoritos($usuario_id);
                $data['pm_no_vistos'] = $this->Usuario->pm_no_vistos($usuario_id);
                $data['pm_vistos'] = $this->Usuario->pm_vistos($usuario_id);
                $data['usuario_perfil'] = TRUE;
            }
        }

        $this->breadcrumbcomponent->add('Home', base_url());
        $this->breadcrumbcomponent->add('Perfil',
                                        base_url() . 'usuarios/perfil/' . $usuario_id);
        $this->breadcrumbcomponent->add($data['usuario']['nick'], base_url());

        $this->template->load("/usuarios/perfil", $data);
    }

    public function domain_exists($email, $record = 'MX'){
       list($user, $domain) = explode('@', $email);
       return checkdnsrr($domain, $record);
    }

    public function usuarios_cercanos($latitud = NULL, $longitud = NULL, $distancia = NULL) {
        $usuarios_cercanos = array();
        if($latitud !== NULL && $longitud !== NULL && $distancia !== NULL) {
            $latitud = (double) $latitud;
            $longitud = (double) $longitud;
            $distancia = (double) $distancia;

            $usuarios_cercanos = $this->Usuario->usuarios_cercanos($latitud, $longitud, $distancia);
        }
        echo json_encode(
                array(
                    'usuarios' => $usuarios_cercanos,
                    'distancia' => $distancia,
                )
            );
    }

    public function editar_perfil($usuario_id = NULL) {
        if(!$this->Usuario->logueado() ||
            $usuario_id === NULL ||
            $this->Usuario->por_id($usuario_id) === FALSE) {

            $mensajes[] = array('error' =>
                "Parametros incorrectos para visualizar la configuracion del perfil del usuario.");
            $this->flashdata->load($mensajes);

            redirect('/frontend/portada/');
        }
        $id_propio = $this->session->userdata('usuario')['id'];
        if($id_propio !== $usuario_id) {
            $mensajes[] = array('error' =>
                "No puedes modificar un perfil que no es el tuyo.");
            $this->flashdata->load($mensajes);

            redirect('/frontend/portada/');
        }
        $reglas = $this->reglas_comunes;
        $reglas[0] = array(
                        'field' => 'nick',
                        'label' => 'Nick',
                        'rules' => array(
                            'trim', 'required',
                            array('existe_nick', function ($nick) {
                                    return !$this->Usuario->existe_nick_id($nick,
                                            $this->session->userdata('usuario')['id']);
                                }
                            )
                        ),
                        'errors' => array(
                            'existe_nick' => 'El nick ya existe, por favor, escoja otro.',
                        )
                    );
        $reglas[1] = array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => array(
                'trim', 'required',
                array('existe_email', function ($email) {
                        return !$this->Usuario->existe_email_id($email,
                                $this->session->userdata('usuario')['id']);
                    }
                ),
                array('dominio_email', function ($email) {
                        return $this->domain_exists($email);
                    }
                ),
            ),
            'errors' => array(
                'existe_email' => 'El email ya existe, por favor, escoja otro.',
                'dominio_email' => 'Este email no puede existir, por favor, vuelva a intertarlo.'
            )
        );

        $reglas[] = array(
            'field' => 'password_old',
            'label' => 'Constraseña Antigua',
            'rules' => array(
                'trim', 'required',
                array('password_old', function ($password_old) {
                        $usuario = $this->Usuario->por_id($this->session->userdata('usuario')['id']);
                        return password_verify($password_old, $usuario['password']);
                    }
                ),
            ),
            'errors' => array(
                'password_old' => "La contraseña antigua no es correcta. Por favor, vuelva a intentarlo.",
            )
        );

        $this->form_validation->set_rules($reglas);
        if ($this->form_validation->run() === TRUE) {

            $valores = $this->input->post();
            unset($valores['editar']);
            unset($valores['password_confirm']);
            unset($valores['password_old']);
            unset($valores['geolocalizacion']);

            $valores['latitud'] = ($valores['latitud'] !== 'null') ? (double) $valores['latitud'] : NULL;
            $valores['longitud'] = ($valores['longitud'] !== 'null') ? (double) $valores['longitud'] : NULL;
            $valores['email_favorito'] = ($valores['email_favorito'] === 't') ? TRUE : FALSE;
            $valores['password'] = password_hash($valores['password'], PASSWORD_DEFAULT);
            $valores['ip'] = $this->blacklist->get_real_ip();
            $this->Usuario->editar($valores, $usuario_id);

            $mensajes[] = array('info' =>
                    "Informacion Personal actualizada.");
            $this->flashdata->load($mensajes);

            redirect('/frontend/portada/');
        }
        $data['usuario'] = $this->Usuario->por_id($usuario_id);

        $this->breadcrumbcomponent->add('Home', base_url());
        $this->breadcrumbcomponent->add('Editar Perfil',
                                        base_url() . 'usuarios/editar_perfil/' . $usuario_id);
        $this->breadcrumbcomponent->add($data['usuario']['nick'], base_url());

        if($data['usuario']['latitud'] == '')
            $data['usuario']['latitud'] = 'null';
        if($data['usuario']['longitud'] == '')
            $data['usuario']['longitud'] = 'null';

        $this->template->load("/usuarios/editar_perfil", $data);
    }

    public function usuarios_nick($nick = NULL) {
        $sugerencias_nick = array();
        $nick_ocupado = false;
        if($nick !== NULL && $this->Usuario->logueado()) {
            $usuario_id = $this->session->userdata('usuario')['id'];
            $nick_ocupado = $this->Usuario->usuarios_nick($nick, $usuario_id);
        }
        if($nick !== NULL) {
            $nick_ocupado = $this->Usuario->usuarios_nick_total($nick);
        }
        if($nick_ocupado) {
            $sugerencia = "";
            for ($i = 0; true; $i++) {
                $sugerencia = $nick . rand();
                if($this->Usuario->logueado()) {
                    if( ! $this->Usuario->usuarios_nick($sugerencia, $usuario_id)) {
                        $sugerencias_nick[] = $sugerencia;
                    }
                } else {
                    if( ! $this->Usuario->usuarios_nick_total($sugerencia)) {
                        $sugerencias_nick[] = $sugerencia;
                    }
                }
                if(count($sugerencias_nick) === 2) {
                    break;
                }
            }
        }
        echo json_encode(
                array(
                    'nick_ocupado' => $nick_ocupado,
                    'sugerencias_nick' => $sugerencias_nick,
                )
            );
    }

    public function usuarios_email($email = NULL) {
        $email_ocupado = false;
        $email = urldecode($email);
        if($email !== NULL && $this->Usuario->logueado()) {
            $usuario_id = $this->session->userdata('usuario')['id'];
            $email_ocupado = $this->Usuario->usuarios_email($email, $usuario_id);
        }
        if($email !== NULL) {
            $email_ocupado = $this->Usuario->usuarios_email_total($email);
        }
        echo json_encode(
                array(
                    'email_ocupado' => $email_ocupado,
                )
            );
    }

    public function insertar_pm($id_usuario = NULL) {
        if (!$this->Usuario->logueado()) {
            $mensajes[] = array('error' =>
                    "No puedes enviar un pm si no estas logueado.");
            $this->flashdata->load($mensajes);
            redirect('/frontend/portada/');
        }
        if($id_usuario === NULL || $this->Usuario->por_id($id_usuario) === FALSE) {
            $mensajes[] = array('error' =>
                "Parametros incorrectos para realizar un pm.");
            $this->flashdata->load($mensajes);

            redirect('/frontend/portada/');
        }
        if ($this->input->post('enviar') !== NULL) {
            $mensaje = $this->input->post();
            $mensaje['receptor_id'] = $id_usuario;
            $mensaje['emisor_id'] = $this->session->userdata('usuario')['id'];
            unset($mensaje['enviar']);

            $reglas = array(
                array(
                    'field' => 'mensaje',
                    'label' => 'Mensaje',
                    'rules' => array(
                        'trim', 'required', 'max_length[500]',
                    ),
                ),
            );

            $this->form_validation->set_rules($reglas);
            if ($this->form_validation->run() === TRUE) {
                if($this->Usuario->usuario_baneado($id_usuario) ||
                   $this->session->userdata('usuario')['admin']) {
                   $mensajes[] = array('error' =>
                       "No se puede enviar un mensaje a un usuario baneado.");
                   $this->flashdata->load($mensajes);
                }
                $this->Usuario->insertar_pm($mensaje);
            } else {
                $mensajes[] = array('error' =>
                    "Se requiere de un texto en mensaje.");
                $this->flashdata->load($mensajes);

                redirect('/usuarios/perfil/' . $id_usuario);
            }
        }

        $mensajes[] = array('info' =>
            "PM realizado correctamente.");
        $this->flashdata->load($mensajes);
        redirect('/usuarios/perfil/' . $id_usuario);
    }

    public function subir_imagen() {
        if (!$this->Usuario->logueado()) {
            $mensajes[] = array('error' =>
                    "No puedes editar un perfil si no estas logueado.");
            $this->flashdata->load($mensajes);
            redirect('/frontend/portada/');
        }

        $data['error'] = array();

        $config['upload_path'] = 'imagenes_usuarios/';
        $config['allowed_types'] = 'jpeg|jpg|jpe';
        $config['overwrite'] = TRUE;
        $config['max_width'] = '5000';
        $config['max_height'] = '5000';
        $config['max_size'] = '500';
        $usuario_id = $this->session->userdata('usuario')['id'];
        $config['file_name'] = $usuario_id . '.jpg';
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('foto')) {
          $data['error'] = $this->upload->display_errors();
        }
        else {
          $data = array('upload_data' => $this->upload->data());
          $imagen = new Imagick($data['upload_data']['full_path']);
          $imagen->adaptiveResizeImage(70, 70);
          $imagen->writeImageFile(fopen("imagenes_usuarios/" . $usuario_id . "_thumbnail.jpeg", "w"));
        }
        $this->template->load('/usuarios/editar_perfil');
    }

    public function borrar_imagen($usuario_id = NULL) {
        if($usuario_id !== NULL) {
            unlink($_SERVER["DOCUMENT_ROOT"] .
                        '/imagenes_usuarios/' .
                        $usuario_id . '.jpg');
        }
    }

    public function obtener_imagen($usuario_id = NULL) {
        $datos = array();
        if($usuario_id !== NULL) {
            if(is_file($_SERVER["DOCUMENT_ROOT"] .
                        '/imagenes_usuarios/' .
                        $usuario_id . '.jpg')):

                $usuario = $this->Usuario->por_id($usuario_id);
                $datos['name'] = $usuario['nick'];
                $datos['imagen'] = $usuario_id . '.jpg';
                $datos['size'] = '3200';
                $datos['type'] = "image/jpeg";
            endif;
        }

        echo json_encode(
            array(
                'imagen' => $datos,
            )
        );
    }

    public function update_pm($pm_id = NULL) {
        if($pm_id !== NULL) {
            $pm_id = (double) $pm_id;

            $pm = $this->Usuario->get_pm($pm_id);

            if($pm !== FALSE) {
                $valores = array();
                $valores['visto'] = ($pm['visto'] === "f") ? TRUE : FALSE;
                $this->Usuario->update_pm($valores, $pm_id);
            }
        }
    }

    public function valorar_vendedor($venta_id = NULL) {
        if (!$this->Usuario->logueado()) {
            $mensajes[] = array('error' =>
                    "No puedes valorar nada si no estas logueado.");
            $this->flashdata->load($mensajes);
            redirect('/frontend/portada/');
        }
        $usuario_id = $this->session->userdata('usuario')['id'];
        if(!$this->Venta->es_comprador($usuario_id, $venta_id)) {
            redirect('/frontend/portada/');
        }

        $venta = $this->Venta->por_id($venta_id);
        if ($this->input->post('valorar') !== NULL) {
            $venta_form = $this->input->post();
            $reglas = array(
                array(
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
                ),
                array(
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
                ),
            );

            $valoracion = array(
                'venta_id' => $venta['venta_id'],
                'valoracion' => $venta_form['valoracion'],
                'valoracion_text' => $venta_form['valoracion_text'],
            );

            $this->Valoracion->insertar_valoracion_comprador($valoracion);
            redirect('/frontend/portada/');
        }

        $data['venta'] = $venta;

        $this->breadcrumbcomponent->add('Home', base_url());
        $this->breadcrumbcomponent->add('Perfil',
                                        base_url() . 'usuarios/perfil/'.$usuario_id);
        $this->breadcrumbcomponent->add('Valorar Vendedor',
                                        base_url() . 'usuarios/valorar_vendedor/'.$data['venta']['venta_id']);
        $this->breadcrumbcomponent->add($data['venta']['vendedor_nick'], base_url());

        $this->template->load('/usuarios/valorar_vendedor', $data);
    }

    public function valorar_comprador($venta_id = NULL) {
        if (!$this->Usuario->logueado()) {
            $mensajes[] = array('error' =>
                    "No puedes valorar nada si no estas logueado.");
            $this->flashdata->load($mensajes);
            redirect('/frontend/portada/');
        }
        $usuario_id = $this->session->userdata('usuario')['id'];
        if(!$this->Venta->es_vendedor($usuario_id, $venta_id)) {
            redirect('/frontend/portada/');
        }

        $venta = $this->Venta->por_id($venta_id);
        if ($this->input->post('valorar') !== NULL) {
            $venta_form = $this->input->post();
            $reglas = array(
                array(
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
                ),
                array(
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
                ),
            );

            $valoracion = array(
                'venta_id' => $venta['venta_id'],
                'valoracion' => $venta_form['valoracion'],
                'valoracion_text' => $venta_form['valoracion_text'],
            );

            $this->Valoracion->insertar_valoracion_vendedor($valoracion);
            redirect('/frontend/portada/');
        }

        $data['venta'] = $venta;

        $this->breadcrumbcomponent->add('Home', base_url());
        $this->breadcrumbcomponent->add('Perfil',
                                        base_url() . 'usuarios/perfil/'.$usuario_id);
        $this->breadcrumbcomponent->add('Valorar Comprador',
                                        base_url() . 'usuarios/valorar_comprador/'.$data['venta']['venta_id']);
        $this->breadcrumbcomponent->add($data['venta']['comprador_nick'], base_url());

        $this->template->load('/usuarios/valorar_comprador', $data);
    }

    public function borrar_compra($venta_id = NULL) {
        if (!$this->Usuario->logueado()) {
            $mensajes[] = array('error' =>
                    "No puedes borrar nada si no estas logueado.");
            $this->flashdata->load($mensajes);
            redirect('/frontend/portada/');
        }
        $usuario_id = $this->session->userdata('usuario')['id'];
        if(!$this->Venta->es_comprador($usuario_id, $venta_id)) {
            redirect('/frontend/portada/');
        }
        $valores = array(
            'comprador_id' => NULL
        );

        $this->Venta->borrar_compra($venta_id, $valores);
        redirect('/frontend/portada/');
    }

    public function existe_imagen($nombre = NULL) {
        $existe = FALSE;
        if($nombre !== NULL) {
            if(is_file($_SERVER["DOCUMENT_ROOT"] . '/imagenes_usuarios/' . $nombre)):
                $existe = TRUE;
            endif;
        }

        echo json_encode(array(
            'existe' => $existe
        ));
    }

    public function ubicacion_manual() {
        $this->load->view('/usuarios/ubicacion_manual');
    }
}
