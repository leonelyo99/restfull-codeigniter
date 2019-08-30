<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente_model extends CI_Model{
	
	public $id;
	public $nombre;
	public $activo;
	public $correo;
	public $zip;
	public $telefono1;
	public $telefono2;
	public $pais;
	public $direccion;

	public function get_cliente($id){
		//traigo la fila que coinside con el id y que este activo
		$this->db->where(array('id'=>$id, 'status'=>'activo'));
		$query = $this->db->get('clientes');

		//la cargo en el objeto
		$row = $query->custom_row_object(0, 'Cliente_model');

		if(isset($row)){
			$row->id = intval($row->id);
			$row->activo = intval($row->activo);
		}

		//lo devuelvo al servicio
		return $row;
	}

	public function set_datos($data_cruda){
		//recibo la data
		foreach ($data_cruda as $nombre_campo => $valor_campo) {
			//por cada campo me fijo si existe ese nombre en mi objeto
			if(property_exists('Cliente_model', $nombre_campo)){
				//meto ese valor en cada una de las propiedades de mi clase
				$this->$nombre_campo=$valor_campo;
			}
		}

		if ($this->activo==NULL) {
			$this->activo = 1;
		}

		$this->nombre = strtoupper($this->nombre);

		//retorno mi clase con los campos de la misma
		return $this;
	}

	public function insert(){

		//consulta para saber si ya esta registrado
		$query = $this->db->get_where('clientes',array('correo'=>$this->correo));
		$cliente_correo = $query->row();

		if(isset($cliente_correo)){
				//existe
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'El correo electronico ya esta registrado'
			);
			return $respuesta;
		}

		//$cliente = $this->Cliente_model->set_datos($data);

		$hecho = $this->db->insert('clientes', $this);

		if($hecho){
			$respuesta = array(
				'err'=>FALSE,
				'mensaje'=>'Registro insertado correctamente',
				'cliente_id'=>$this->db->insert_id()
			);
		}else{
				//si no sucedio
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'error al insertar',
				'error'=>$this->db->_error_message(),
				'error_num'=>$this->db->_error_number()
			);
		}
		return $respuesta;
	}

	public function delete($cliente_id){

		$this->db->set('status', 'borrado', TRUE);
		$this->db->where('id', $cliente_id);
		$hecho = $this->db->update('clientes');

		if($hecho){
			$respuesta = array(
				'err'=>FALSE,
				'mensaje'=>'Registro eliminado correctamente'
			);
		}else{
				//si no sucedio
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'error al cambio de estatus',
				'error'=>$this->db->_error_message(),
				'error_num'=>$this->db->_error_number()
			);
		}
		return $respuesta;
	}

	public function update(){

		//verificar el correo
		$this->db ->where('correo =',$this->correo);
		$this->db ->where('id !=',$this->id);
		$query = $this->db->get('clientes');

		$cliente_correo = $query->row();

		if(isset($cliente_correo)){
				//existe
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'El correo electronico ya esta registrado por otro usuario'
			);
			return $respuesta;
		}

		$this->db->reset_query();
		$this->db->where('id',$this->id);
		$hecho = $this->db->update('clientes', $this);

		if($hecho){
			$respuesta = array(
				'err'=>FALSE,
				'mensaje'=>'Registro actualizado correctamente',
				'cliente_id'=>$this->id
			);
		}else{
				//si no sucedio
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'error al actualizar',
				'error'=>$this->db->_error_message(),
				'error_num'=>$this->db->_error_number()
			);
		}
		return $respuesta;
	}

}