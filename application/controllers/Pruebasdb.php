<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');


class Pruebasdb extends CI_Controller {

	public function __construct(){
		//llamado del constructor del padre
		parent::__construct();
		//cargo la base de datos porque la van a usar todos
		$this->load->database();
		//cargo el helper
		$this->load->helper('utilidades');
	}

	public function eliminar(){

		$this->db->where('id', 1);
		$this->db->delete('test');

		echo "Se borro ok";		
	}

	public function insert(){
		//////////insertar un solo registro a la vez
		// $data = array(
		// 	'nombre' => 'Leonel',
		// 	'apellido' => 'maZZAn'
		// );

		// $data = capitalizar_todo($data);

		// $this->db->insert('test', $data);
		
		// $respuesta = array(
		// 	'err'=>FALSE,
		// 	'id_insertado'=>$this->db->insert_id()
		// );

		// echo json_encode($respuesta);
		
		///////////insertar multiples registros
		$data = array(
			array(
				'nombre' => 'paco',
				'apellido' => 'perez'
			),
			array(
				'nombre' => 'juan',
				'apellido' => 'perez'
			)
		);
		$this->db->insert_batch('test', $data);

		echo $this->db->affected_rows();
	}

	public function update(){
		$data = array(
			'nombre' => 'facu',
			'apellido' => 'aballay'
		);

		$data = capitalizar_todo($data);

		$this->db->where('id', 2);
		$this->db->update('test', $data);

		echo "Todo ok";
	}


	public function tabla(){
		//$this->load->database();
		//tipos de consultas
		//$query = $this->db->get('clientes', 10, 10); //limit de la p 10 + 10 
		//$this->db->select('id, nombre, correo'); //select id nombre correo
		//$query = $this->db->get_where('clientes', array('id' => 1)); //where
		//$this->db->select_max('id'); //max o min
		//$this->db->select_avg('id'); //promedio
		//$this->db->select_sum('id'); //suma de campo
		//secmentarla
		$this->db->select('pais, COUNT(*) as clientes');
		$this->db->from('clientes');
		//$this->db->where('nombre', 'XAVIER NIEVES'); //where mas especifico se pueden anidar varios
		//$this->db->where('id', 10);
		//$this->db->or_where('id', 2); //anidar where con or
		//$this->db->like('nombre', 'LINDSEY'); //operador like
		$this->db->group_by('pais'); //group by
		$this->db->order_by('pais', 'ASC'); //order by

		$query = $this->db->get(); //ejecuta la consulta


		echo json_encode($query->result());
	}

	public function clientes_beta(){
		
		//$this->load->database();

		$query = $this->db->query('SELECT id, nombre, correo FROM clientes');

		// foreach ($query->result_array() as $row)
		// {
		// 	echo $row['id'];
		// 	echo $row['nombre'];
		// 	echo $row['correo'];
		// }
		// echo 'Total de registro es '.$query->num_rows();
		
		$respuesta = array(
			'err'=> FALSE,
			'mensaje'=>'Registros cargados correctamente',
			'total_registros'=> $query->num_rows(),
			'clientes'=> $query->result()
		);

		echo json_encode($respuesta);
	}

	public function cliente($id){
		//$this->load->database();

		$query = $this->db->query('SELECT id, nombre, correo FROM clientes where id= '.$id);

		$fila = $query->row();

		if(isset($fila)){
			//FILA EXISTE
			$respuesta = array(
				'err'=> FALSE,
				'mensaje'=>'Registro cargado correctamente',
				'total_registros'=> $query->num_rows(),
				'cliente'=> $fila
			);
		}else{
			//FILA NO EXISTE
			$respuesta = array(
				'err'=> TRUE,
				'mensaje'=>'El registro no se cargo',
				'total_registros'=> $query->num_rows(),
				'cliente'=> null
			);
		}
		echo json_encode($respuesta);
	}
}