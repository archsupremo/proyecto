<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('html2pdf');
    }

    private function createFolder() {
        if(!is_dir("./files")) {
            mkdir("./files", 0777);
            mkdir("./files/pdfs", 0777);
        }
    }


    public function index() {
        if(!$this->Usuario->logueado()) {
            $mensajes[] = array('error' =>
                "Parametros incorrectos para obtener el pdf.");
            $this->flashdata->load($mensajes);

            redirect('/frontend/portada/');
        }
        $this->createFolder();

        //importante el slash del final o no funcionará correctamente
        $this->html2pdf->folder('./files/pdfs/');

        //establecemos el nombre del archivo
        $usuario = $this->session->userdata('usuario');
        $this->html2pdf->filename($usuario['id'].'.pdf');

        //establecemos el tipo de papel
        $this->html2pdf->paper('a4', 'portrait');

        //datos que queremos enviar a la vista, lo mismo de siempre
        // $data = array(
        //     'title' => 'Listado usuarios en pdf',
        //     // 'usuarios' => $this->pdf_model->getUsuarios()
        // );

        $usuario_id = $usuario['id'];
        $data['usuario_nick'] = $usuario['nick'];
        $data['articulos_usuarios'] = $this->Usuario->por_id_vista($usuario_id);
        $data['articulos_vendidos'] = $this->Usuario->ventas_usuario($usuario_id);
        $data['articulos_comprados'] = $this->Usuario->compras_usuario($usuario_id);

        $data['valoraciones_compras'] = $this->Usuario->valoraciones_a_comprador($usuario_id);
        $data['valoraciones_ventas'] = $this->Usuario->valoraciones_a_vendedor($usuario_id);

        $data['articulos_favoritos'] = $this->Articulo->articulos_favoritos($usuario_id);
        $data['pm_no_vistos'] = $this->Usuario->pm_no_vistos($usuario_id);
        $data['pm_vistos'] = $this->Usuario->pm_vistos($usuario_id);

        //hacemos que coja la vista como datos a imprimir
        //importante utf8_decode para mostrar bien las tildes, ñ y demás
        $this->html2pdf->html($this->load->view('/pdf/pdf', $data, true));

        //si el pdf se guarda correctamente lo mostramos en pantalla
        if($this->html2pdf->create('save')) {
            $this->show();
        }
    }

    //esta función muestra el pdf en el navegador siempre que existan
    //tanto la carpeta como el archivo pdf
    private function show() {
        if(is_dir("./files/pdfs")) {
            $usuario = $this->session->userdata('usuario');

            $filename = $usuario['id'].".pdf";
            $route = base_url("files/pdfs/".$filename);
            if(file_exists("./files/pdfs/".$filename)) {
                header('Content-type: application/pdf');
                readfile($route);
            }
            unlink($_SERVER["DOCUMENT_ROOT"].'/files/pdfs/'.$filename);
        }
    }

    //funcion que ejecuta la descarga del pdf
    private function downloadPdf() {
        //si existe el directorio
        if(is_dir("./files/pdfs")) {
            $usuario = $this->session->userdata('usuario');

            //nombre del archivo
            $filename = $usuario['id'].".pdf";
            //ruta completa al archivo
            $route = base_url("./files/pdfs/".$filename);
            //si existe el archivo empezamos la descarga del pdf
            if(file_exists("./files/pdfs/".$filename)) {
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header('Content-disposition: attachment; filename='.basename(md5($route).'.pdf'));
                header("Content-Type: application/pdf");
                header("Content-Transfer-Encoding: binary");
                header('Content-Length: '. filesize($route));
                readfile($route);
            }
        }
    }

    //función para crear y enviar el pdf por email
    //ejemplo de la libreria sin modificar
    private function mail_pdf() {
        $usuario = $this->session->userdata('usuario');

        //establecemos la carpeta en la que queremos guardar los pdfs,
        //si no existen las creamos y damos permisos
        $this->createFolder();

        //importante el slash del final o no funcionará correctamente
        $this->html2pdf->folder('./files/pdfs/');

        //establecemos el nombre del archivo
        $this->html2pdf->filename($usuario['id'].'.pdf');

        //establecemos el tipo de papel
        $this->html2pdf->paper('a4', 'portrait');

        //datos que queremos enviar a la vista, lo mismo de siempre
        $data = array(
            'title' => 'Listado de usuarios en pdf',
            'usuarios' => $this->pdf_model->getUsuarios()
        );

        //hacemos que coja la vista como datos a imprimir
        //importante utf8_decode para mostrar bien las tildes, ñ y demás
        $this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf', $data, true)));

        //Check that the PDF was created before we send it
        if($path = $this->html2pdf->create('save')) {
            $this->load->library('email');
            $this->email->from('your@example.com', 'Your Name');
            $this->email->to('israel965@yahoo.es');
            $this->email->subject('Email PDF Test');
            $this->email->message('Testing the email a freshly created PDF');
            $this->email->attach($path);
            $this->email->send();

            echo "El email ha sido enviado correctamente";
        }
    }
}
/* End of file pdf_ci.php */
/* Location: ./application/controllers/pdf_ci.php */
