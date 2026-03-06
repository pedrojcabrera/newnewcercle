<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\InscritosModel;
use App\Models\InvitadosModel;
use App\Models\ContactosModel;
use App\Models\EnEsperaModel;


class Traspaso2 extends BaseController
{

	public function arrInscritos() {

		$inscritosM = new InscritosModel; 
		$invitadosM = new InvitadosModel; 
		$contactosM = new ContactosModel;
		$esperandoM = new EnEsperaModel;

		$inscritos = $inscritosM->findAll();
		
		foreach($inscritos as $inscrito) {

			if($inscrito->via == 'Invitado' || $inscrito->via == 'registro') {
				
				$contacto = $contactosM
							->where('nombre',$inscrito->nombre)
							->where('apellidos',$inscrito->apellidos)
							->first();

				if($contacto) {
					
					$data = ['id_contacto' => $contacto->id];
									
					$inscritosM->update($inscrito->id, $data);
				}
			}
		}

		$inscritos = $inscritosM->findAll();
		
		foreach($inscritos as $inscrito) {

			if($inscrito->via == 'Invitado' || $inscrito->via == 'registro') {
				
				$invitado = $invitadosM
							->where('nombre',$inscrito->nombre)
							->where('apellidos',$inscrito->apellidos)
							->where('inscrito',1)
							->first();
							
				if($invitado) {

					$data = ['fecha' => $invitado->fecha];

					$inscritosM->update($inscrito->id, $data);
				}
			}
		}

		$esperandoM = $esperandoM->findAll();
		
		foreach($esperandoM as $emailIns) {

			$contacto = $contactosM
						->where('nombre',$emailIns->nombre)
						->where('apellidos',$emailIns->apellidos)
						->first();

			if($contacto){

				$data = ['id_contacto' => $contacto->id];
	
				$esperandoM->update($emailIns->id, $data);
			}
		}
	}
}