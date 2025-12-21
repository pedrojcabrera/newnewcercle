<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Traspaso extends BaseController
{

	public function arrDatabase() {
		# tablas a tratar

		$tablas = array('agenda_correos','cercledartfoios','contactos','correos','enlaces_de_interes','galerias','inscripciones','neventos','tiposeventos','usuarios', 'emailsinscripciones', 'inscritos', 'invitados');

		// Datos de conexión

		$servidor = "localhost";
		$usuario = "qvn651";
		$password = "Cercle46134";
		$base_de_datos = "qvn651";

		// Crear una conexión

		$conexion = new \mysqli($servidor, $usuario, $password, $base_de_datos);

		// Verificar la conexión

		if ($conexion->connect_error) {
			die("Conexión fallida: " . $conexion->connect_error);
		}

		$sql="SHOW TABLES";

		$resultado = $conexion->query($sql);

		while ($row = $resultado->fetch_assoc()) {

			$tables[] = $row["Tables_in_qvn651"];

		}

		echo '<h3>Listado de Tablas Iniciales</h3>';

		foreach ($tables as $tabla) {

			echo($tabla."<br>");

			if(!in_array($tabla, $tablas)) {

				$tablasEliminadas[] = $tabla;

				$sql = "DROP TABLE ".$tabla;
				
				if(!$conexion->query($sql)) {
					echo ("Error al eliminar la tabla ".$tabla."<br>");
				}

			} else {
				$tablasResistentes[] = $tabla;
			}
		}

		echo '<h3>Tablas que se van</h3>';

		if(isset($tablasEliminadas)){
			foreach($tablasEliminadas as $tabla){
				echo $tabla."<br>";
			}
		}

		echo '<h3>Tablas que se quedan</h3>';

		foreach($tablasResistentes as $tabla){
			echo $tabla."<br>";
		}

		echo "<hr><h3>Cambios en tablas</h3>";

		$sql = "ALTER TABLE neventos
				MODIFY texto text,
				MODIFY texto_carta text";

		if($conexion->query($sql)) {
			echo "Se actualizaron los campos texto y texto_carta de neventos<br>";
		}

		$tabla = "inscripciones";
		$columna = "fecha_nacimiento";

		$sql_comprobar = "SHOW COLUMNS FROM $tabla LIKE '$columna'";
		$resultado = $conexion->query($sql_comprobar);

		// Verificar si la columna existe

		if ($resultado->num_rows > 0) {
			// Si la columna existe, eliminarla

			$sql_eliminar = "ALTER TABLE $tabla DROP COLUMN $columna";
			if ($conexion->query($sql_eliminar)) {
				echo "Se eliminó el campo fecha_nacimiento de $tabla<br>";
			}
		}

		$tabla = "contactos";
		$columna = "fecha_nacimiento";

		$sql_comprobar = "SHOW COLUMNS FROM $tabla LIKE '$columna'";
		$resultado = $conexion->query($sql_comprobar);

		// Verificar si la columna existe

		if ($resultado->num_rows > 0) {
			// Si la columna existe, eliminarla

			$sql_eliminar = "ALTER TABLE $tabla DROP COLUMN $columna";
			if ($conexion->query($sql_eliminar)) {
				echo "Se eliminó el campo fecha_nacimiento de $tabla<br>";
			}
		}

		$tabla = "contactos";
		$columna = "tokenunico";
		$tipo = "VARCHAR(60)";
		$despuesde = "telefono";

		$columna_nombre = "nombre";
		$columna_apellidos = "apellidos";

		$sql_comprobar = "SHOW COLUMNS FROM $tabla LIKE '$columna'";
		$resultado = $conexion->query($sql_comprobar);

		// Verificar si la columna existe

		if ($resultado->num_rows < 1) {
			// Si la columna no existe, crearla

			$sql_insertar = "ALTER TABLE $tabla ADD COLUMN $columna $tipo AFTER $despuesde";

			if ($conexion->query($sql_insertar)) {
				echo "Se insertó el campo tokenunico en $tabla<br>";
				
				$sql_consulta = "SELECT id, $columna_nombre FROM $tabla";
				$resultado = $conexion->query($sql_consulta);
		
				if($resultado->num_rows > 0) {
		
					$columna_a_actualizar = 'tokenunico';

					$registros = 0;
					while($row = $resultado->fetch_object()) {
						$id = $row->id;
						
						$valor_final = password_hash($id, PASSWORD_DEFAULT);
		
						$sql_update = "UPDATE $tabla SET $columna_a_actualizar = '$valor_final' WHERE id = $id";
		
						if($conexion->query($sql_update)) {
							$registros++;
						}
					}
					echo "Se encriptaron $registros registros en el campo tokenunico de $tabla<br>";
				}
			}
		}

		$sql = "ALTER TABLE usuarios MODIFY pass VARCHAR(60)";
				
		if($conexion->query($sql)) {
			echo "Se modificó la longitud del campo pass de 32 a 60 en registros de usuarios<br>";
		}

		echo "<hr><h3>Cambios en Carpetas</h3>";
		echo "<hr><h4>Carpeta galerias</h4>";

		// Obtener el contenido de carpeta galerias

		$dirOrigen = "paraTraspaso/galerias";
		
		$datosOrigen = scandir($dirOrigen);

		foreach($datosOrigen as $datoOrigen){
			$posicion = strpos($datoOrigen, '_');
			if ($posicion !== false) {
				$resultado = intval(substr($datoOrigen, 0, $posicion));
				$dirDestino = "galerias/".$resultado;

				if(!file_exists($dirDestino)) {
					mkdir($dirDestino, 0777, true);
					echo "Creada la carpeta $dirDestino<br>";

					$contenido = scandir($dirOrigen."/".$datoOrigen);

					foreach($contenido as $elemento) {
						$origen = $dirOrigen."/$datoOrigen/$elemento";
						$destino = $dirDestino."/$elemento";

						if(!file_exists($destino)) {
							if(copy($origen, $destino)) {
								echo "-> Copiado el archivo $destino<br>";
							}
						}
					}
				}
			}
		}
		echo "<hr><h4>Carpeta neventos</h4>";

		// Obtener el contenido de carpeta neventos

		$dirOrigen = "paraTraspaso/imgusr/neventos";
		
		$datosOrigen = scandir($dirOrigen);

		foreach($datosOrigen as $datoOrigen){

			if($datoOrigen == '.' || $datoOrigen == "..") continue;
			
			$dirDestino = "imgEventos/".$datoOrigen;

			if(is_dir($dirOrigen."/$datoOrigen")) {
				if(!file_exists($dirDestino)) {
					mkdir($dirDestino, 0777, true);
					echo "Creada la carpeta $dirDestino<br>";
				}
				$contenido = scandir($dirOrigen."/".$datoOrigen);
				foreach($contenido as $elemento) {
					$origen = $dirOrigen."/$datoOrigen/$elemento";
					$destino = $dirDestino."/$elemento";
					if(!file_exists($destino)) {
						if(copy($origen, $destino)) {
							echo "-> Copiado el archivo $destino<br>";
						}
					}
				}
			} else {
				$origen = $dirOrigen."/$datoOrigen";
				$destino = $dirDestino;

				if(!file_exists($destino)) {

					if(copy($origen, $destino)) {
						echo "-> Copiado el archivo $destino<br>";
					}
				}
			}
		}
		echo "<hr><h4>Fotos galeristas</h4>";

		$sql = "SELECT * FROM usuarios";
		$resultado = $conexion->query($sql);

		while($usuario = $resultado->fetch_object()) {
			$foto = $usuario->id;
			$user = $usuario->user;
			$origen = "paraTraspaso/imgusr/galerias/$user/foto.jpg";
			$destino = "fotosUsuarios/$foto.jpg";
			if(file_exists($origen)){
				if(!file_exists($destino)) {
					copy($origen, $destino);
					echo "copiada la foto ($foto) de $user<br>";
				}
			}
			$origen = "paraTraspaso/imgusr/galerias/foto.jpg";
			$destino = "fotosUsuarios/sinfoto.jpg";
			if(file_exists($origen)){
				if(!file_exists($destino)) {
					copy($origen, $destino);
					echo "copiada la foto genérica<br>";
				}
			}
		}
	}
}