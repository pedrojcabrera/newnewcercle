<?php

namespace App\Controllers\Admin;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\Models\EventosModel;
use App\Models\InvitadosModel;
use App\Models\InscritosModel;
use App\Models\EnEsperaModel;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pdf extends BaseController {
   
   public function listarInscritos($id) {
         // Instancia de los modelos
         $eventosModel = new EventosModel();
         $inscritosModel = new InscritosModel();

         // Obtener los datos del evento y de los inscritos
         $evento = $eventosModel->find($id);
         $inscritos = $inscritosModel->where('id_evento', $id )->findAll();

         $data = [
            'evento' => $evento->id,
            'titulo' => $evento->titulo,
            'inscritos' => $inscritos,
         ];

         // Configuración de Dompdf
         $options = new Options();
         $options->set('isHtml5ParserEnabled', true);
         $options->set('isRemoteEnabled', true);
         $dompdf = new Dompdf($options);

         // Generar el contenido HTML del PDF
         $html = view('admin/pdf/inscritos', $data);

         // Cargar el HTML en Dompdf
         $dompdf->loadHtml($html);

         // Configurar el tamaño de la página y la orientación
         $dompdf->setPaper('A4', 'portrait');

         // Renderizar el PDF
         $dompdf->render();

         // Descargar el PDF generado
         $dompdf->stream("Inscritos_Evento_".$evento->id.".pdf", ["Attachment" => false]);

         exit;
   }

   public function listarInvitados($id) {
      // Instancia de los modelos
      $eventosModel = new EventosModel();
      $invitadosModel = new InvitadosModel();

      // Obtener los datos del evento y de los inscritos
      $evento = $eventosModel->find($id);
      $invitados = $invitadosModel->where('id_evento', $id )->findAll();

      $data = [
         'evento' => $evento->id,
         'titulo' => $evento->titulo,
         'invitados' => $invitados,
      ];

      // Configuración de Dompdf
      $options = new Options();
      $options->set('isHtml5ParserEnabled', true);
      $options->set('isRemoteEnabled', true);
      $dompdf = new Dompdf($options);

      // Generar el contenido HTML del PDF
      $html = view('admin/pdf/invitados', $data);

      // Cargar el HTML en Dompdf
      $dompdf->loadHtml($html);

      // Configurar el tamaño de la página y la orientación
      $dompdf->setPaper('A4', 'portrait');

      // Renderizar el PDF
      $dompdf->render();

      // Descargar el PDF generado
      $dompdf->stream("Invitados_Evento_".$evento->id.".pdf", ["Attachment" => false]);

      exit;
   }

   public function listarEnEspera($id) {
         // Instancia de los modelos
         $eventosModel = new EventosModel();

         $enEsperaModel = new EnEsperaModel();

         // Obtener los datos del evento y de los desesperados
         $evento = $eventosModel->find($id);
         $esperando = $enEsperaModel->where('id_evento', $id )
                                    ->findAll();

         $data = [
            'evento' => $evento->id,
            'titulo' => $evento->titulo,
            'esperando' => $esperando,
         ];

         // Configuración de Dompdf
         $options = new Options();
         $options->set('isHtml5ParserEnabled', true);
         $options->set('isRemoteEnabled', true);
         $dompdf = new Dompdf($options);

         // Generar el contenido HTML del PDF
         $html = view('admin/pdf/enEspera', $data);

         // Cargar el HTML en Dompdf
         $dompdf->loadHtml($html);

         // Configurar el tamaño de la página y la orientación
         $dompdf->setPaper('A4', 'portrait');

         // Renderizar el PDF
         $dompdf->render();

         // Descargar el PDF generado
         $dompdf->stream("En_Espera_Evento_".$evento->id.".pdf", ["Attachment" => false]);

         exit;
   }

}