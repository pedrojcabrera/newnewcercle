<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Listado de Inscritos</title>
   <style>
   body {
      font-family: Arial, sans-serif;
      /* margin: 20px; */
   }

   table {
      width: 100%;
      border-collapse: collapse;
   }

   table th,
   table td {
      border: 1px solid #ddd;
      padding: 8px 5px;
      text-align: left;
   }

   table th {
      background-color: #f2f2f2;
   }

   h1 {
      text-align: center;
      margin-bottom: 20px;
   }

   h2 {
      width: 100%;
      text-align: center;
   }

   .cabecera {
      text-align: center;
   }

   img {
      width: 50%;
   }
   </style>
</head>

<body>
   <div class="cabecera">
      <img src="<?= base_url('recursos/imagenes/anagramaColor.png') ?>" alt="">
      <h1 style="color: darkgrey;">Listado de Inscritos</h1>
      <h2 style="white-space: nowrap;"><?= $titulo ?></h2>
   </div>
   <table>
      <thead>
         <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Vía</th>
            <th>Fecha</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($inscritos as $inscrito): ?>
         <tr>
            <td><?= trim($inscrito->nombre.' '.$inscrito->apellidos) ?></td>
            <td><?= $inscrito->email ?></td>
            <td><?= $inscrito->telefono ?></td>
            <td><?= $inscrito->via ?></td>
            <td style="font-size: 14px;text-align:center;"><?= date('d/m/Y', strtotime($inscrito->fecha)) ?>
               <hr><?= date('H:i', strtotime($inscrito->fecha))." h" ?>
            </td>
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</body>

</html>