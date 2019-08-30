<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
use Restserver\Libraries\REST_Controller;
require( APPPATH.'/libraries/REST_Controller.php');



class Clientes extends REST_Controller {

	public function __construct(){
		///////////llamado del constructor del padre
		parent::__construct();
		////////////cargo la base de datos porque la van a usar todos
		$this->load->database();
		/////////////cargo el helper
		//$this->load->helper('utilidades');
		/////////////cargo el modelo
		$this->load->model('Cliente_model');
	}


	public function cliente_put(){
		//traigp los datos del post
		$data = $this->put();
		//cargo la libreria
		$this->load->library('form_validation');
		//le digo los datos a validar
		$this->form_validation->set_data($data);
		//que campo va a validat, con que regla
		//comentadas porque salen del arhivo config form
		//$this->form_validation->set_rules('correo','correo electronico','required|valid_email');
		//$this->form_validation->set_rules('nombre','nombre','required|min_length[3]');

		//valido el formulario y observo la respuesto true o false
		if($this->form_validation->run('cliente_put')){
			$cliente = $this->Cliente_model->set_datos($data);

			$respuesta = $cliente->insert();

			if($respuesta['err']){
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			}else{
				$this->response($respuesta);
			}


		}else{
			//Algo mal
			//$this->response('Todo mal');
			
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores eb el envio de la informacion',
				'errores'=> $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function cliente_post(){
		//traigp los datos del post
		$data = $this->post();
		//traigo el id
		$ciente_id = $this->uri->segment(3);
		//cargo la libreria
		$this->load->library('form_validation');
		//le digo los datos a validar
		$data['id'] = $ciente_id;
		$this->form_validation->set_data($data);
			
		//valido el formulario y observo la respuesto true o false
		if($this->form_validation->run('cliente_post')){
			$cliente = $this->Cliente_model->set_datos($data);

			$respuesta = $cliente->update();

			if($respuesta['err']){
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			}else{
				$this->response($respuesta);
			}


		}else{
			//Algo mal
			//$this->response('Todo mal');
			
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores eb el envio de la informacion',
				'errores'=> $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function paginar_get(){
		//cargo el helper
		$this->load->helper('paginacion');
		
		///tomo los datos por la url
		$pagina 	= $this->uri->segment(3);
		$por_pagina = $this->uri->segment(4);

		////invoco la funcion de helper que me va a paginar y me va a mostrar cuantos quiero por pagina
		// un campo
		$respuesta=paginar_todo('clientes', $pagina, $por_pagina);


		//mando la respuesta
		$this->response($respuesta);
	}

	public function cliente_delete(){		
		///tomo los datos por la url
		$cliente_id = $this->uri->segment(3);

		$respuesta = $this->Cliente_model->delete($cliente_id);
		
		$this->response($respuesta);
	}

	public function cliente_get(){
		
		//saco el id de la url
		$cliente_id = $this->uri->segment(3);

		//validar el cliente id
		if (!isset($cliente_id)) {
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'Es necesario el id del cliente'
			);

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}

		//lo paso al modelo
		$cliente = $this->Cliente_model->get_cliente($cliente_id);

		//cargo, valido y lo devuelto para que se muestre
		if(isset($cliente)){
			$respuesta=array(
				'err'=>FALSE,
				'mensaje'=>'Registro cargado correctamente',
				'cliente'=>$cliente
			);

			$this->response($respuesta);
		}else{
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'El registro con el id '.$cliente_id.', no exste',
				'cliente'=>null
			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
}