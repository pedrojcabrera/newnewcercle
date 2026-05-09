<?php
session_start();

$camino_cercle = "../../../";
$camino_root = "../../";

require_once $camino_root."bd.php";
require_once $camino_root."rutinas.php";

$camino_eventos = $camino_cercle . "imgusr/neventos/ev_";
$camino_pdfs = $camino_cercle . "pdfusr/";

$hoy = date('Y-m-d');

$txtID = isset($_GET['txtID']) ? $_GET['txtID'] : "";

if (isset($_POST['eliminar_foto'])) {
    unlink($_POST['eliminar_foto']);
}

if (isset($_GET['txtID'])) {
    $query = "SELECT * FROM neventos WHERE id = $txtID LIMIT 1";
    $res = $db->query($query);
    if ($res->num_rows == 0) {
        header("Location: index.php");
    }

    $evento = $res->fetch_assoc();
}

$query = "SELECT * FROM tiposeventos";
$res = $db->query($query);
while ($tipos = $res->fetch_assoc()) {
    $lista_tipos[$tipos['eventotipo']] = $tipos['eventonombre'];
}

if (isset($_POST['submit'])) {

    $id = isset($_POST['submit']) ? $_POST['submit'] : "";
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : "";
    $eventotipo = isset($_POST['eventotipo']) ? $_POST['eventotipo'] : "";
    $desde = isset($_POST['desde']) ? $_POST['desde'] : "";
    $hasta = isset($_POST['hasta']) ? $_POST['hasta'] : "";
    $texto = isset($_POST['texto']) ? trim($_POST['texto']) : "";
    $visible = isset($_POST['visible']) ? 1 : 0;
    $inscripcion = isset($_POST['inscripcion']) ? 1 : 0;

    $desde_inscripcion = (!empty($_POST['desde_inscripcion'])) ? $_POST['desde_inscripcion'] : null;
    $hasta_inscripcion = (!empty($_POST['hasta_inscripcion'])) ? $_POST['hasta_inscripcion'] : null;

    $inscripcion_invitacion = isset($_POST['inscripcion_invitacion']) ? 1 : 0;
    $texto_carta = isset($_POST['texto_carta']) ? trim($_POST['texto_carta']) : '';
    //$pdf_adjunto = isset($_POST['pdf_adjunto']) ? $_POST['pdf_adjunto'] : "";
    $socio = isset($_POST['socio']) ? 1 : 0;
    $alumno = isset($_POST['alumno']) ? 1 : 0;
    $pdalumno = isset($_POST['pdalumno']) ? 1 : 0;
    $pintor = isset($_POST['pintor']) ? 1 : 0;
    $dtaller = isset($_POST['dtaller']) ? 1 : 0;
    $amigo = isset($_POST['amigo']) ? 1 : 0;

    if (!is_null($desde_inscripcion)) { 
        $desde_ins = $desde_inscripcion;
    } else {
        $desde_ins = null;
    }
    if (!is_null($hasta_inscripcion)) { 
        $hasta_ins = $hasta_inscripcion;
    } else {
        $hasta_ins = null;
    }

    $query = "UPDATE neventos SET
        titulo = '$titulo',
        eventotipo = '$eventotipo',
        desde = '$desde',
        hasta = '$hasta',
        visible = '$visible',
        inscripcion = '$inscripcion',
        inscripcion_invitacion = '$inscripcion_invitacion',
        socio = '$socio',
        alumno = '$alumno',
        pdalumno = '$pdalumno',
        pintor = '$pintor',
        dtaller = '$dtaller',
        amigo = '$amigo',
        desde_inscripcion = '$desde_ins',
        hasta_inscripcion = '$hasta_ins',
        texto_carta = '$texto_carta',
        texto = '$texto'";
        
    $query .= " WHERE id = '$id'";

    // $query = htmlspecialchars($query);
    // $query = str_replace("'","",$query);

//    muestra($query);

    $res = $db->query($query);

    if ($res->errno) {
        exit("Fallo al updatear en MySQLi: (" . $res->errno . ") " . $res->error);
    }

    if ($res) {
        $eve_id = $txtID;
        if (!file_exists($camino_eventos . $eve_id . "/.")) {
            mkdir($camino_eventos . $eve_id, 0777, true);
        }
        if (!is_dir($camino_pdfs)) {
            mkdir($camino_pdfs, 0777, true);
        }
    }
    if (!empty($_FILES['cartel']['tmp_name'])) {
        if (is_uploaded_file($_FILES['cartel']['tmp_name'])) {
            $eve_id = $txtID;
            $cartel = $camino_eventos . $eve_id . "/cartel.jpg";
            if (file_exists($cartel)) {
                unlink($cartel);
            }
            muestra(1);
            if (!move_uploaded_file($_FILES['cartel']['tmp_name'], $cartel)) {
                muestra(2);
                $mensaje = "Fallo al intentar mover " . $_FILES['cartel']['tmp_name'] . " a $cartel";
            } else {
                muestra(3);
                $img = resize_image($cartel, 350, 500, false);
            }
            exit;
        }
    }

    foreach ($_FILES['fotos']['tmp_name'] as $origen) {
        if (!empty($origen)) {
            if (is_uploaded_file($origen)) {
                $eve_id = $txtID;
                $destino = $camino_eventos . $eve_id . "/" . rand(1000000, 9999999) . ".jpg";
                if (file_exists($destino)) {
                    unlink($destino);
                }
                if (!move_uploaded_file($origen, $destino)) {
                    $mensaje = "Fallo al intentar mover $origen  a $destino";
                } else {
                    $img = resize_image($destino, 350, 500, false);
                }
            }
        }
    }
    if (!empty($_FILES['pdf_adjunto']['tmp_name'])) {
        if (is_uploaded_file($_FILES['pdf_adjunto']['tmp_name'])) {
            $pdf_adjunto = $camino_pdfs . 'pdf_' . $evento['id'] . ".pdf";
            if (file_exists($pdf_adjunto) and !is_dir($pdf_adjunto)) {
                unlink($pdf_adjunto);
            }
            if (!file_exists($pdf_adjunto)) {
                if (!move_uploaded_file($_FILES['pdf_adjunto']['tmp_name'], $pdf_adjunto)) {
                    $mensaje = "Fallo al intentar mover " . $_FILES['pdf_adjunto']['tmp_name'] . " a $pdf_adjunto";
                } else {
                    $pdf_adjunto = 'pdf_' . $evento['id'] . ".pdf";
                    $query = "UPDATE neventos SET pdf_adjunto = '$pdf_adjunto' WHERE id = $id";
                    $res = $db->query($query);
                }
            }
        }
    }
    header("Location:index.php");
    exit();
}

$cartel = $camino_eventos . $evento['id'] . "/cartel.jpg";

require_once $camino_root."templates/header.php";

?>

<div class="card col-12 mx-auto">
    <div class="card-header">
        Edición de Eventos
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row border mb-3">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" value="<?=$evento['titulo']?>"
                        placeholder="Título" required>
                </div>
                <div class="mb-3 mt-1 col-6">
                    <select class="form-select form-select-sm" name="eventotipo" id="eventotipo">
                        <?php foreach ($lista_tipos as $clave => $valor): ?>
                        <option value="<?=$clave?>" <?=$clave == $evento['eventotipo'] ? 'selected' : ''?>>
                            <?=strtoupper($valor)?></option>
                        <?php endforeach?>
                    </select>
                </div>
                <div class="mb-2 mt-2 col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="<?=$evento['visible']?>" id="visible"
                            name="visible" <?=$evento['visible'] == 1 ? 'checked' : ''?>>
                        <label class="form-check-label" for="visible">
                            Marcar si el evento es visible en la Web
                        </label>
                    </div>
                </div>
            </div>
            <div class="row mb-3 border">
                <div class="mb-3">
                    <div class="cartel mx-auto mb-3 mt-3 text-center">
                        <?php if ($cartel): ?>
                        <img src="<?=$cartel?>" width="600">
                        <?php endif?>
                    </div>
                    <label for="cartel" class="form-label">Seleccione o cambie el Cartel</label>
                    <input type="file" class="form-control" name="cartel" id="cartel" accept="image/jpg,image/jpeg"
                        placeholder="Seleccione el Cartel">
                </div>
                <div class=" mb-3">
                    <?php
                        $pdf = 'pdf_' . $evento['id'] . ".pdf";
                        //muestra(empty($pdf) ? " no tiene" : $pdf);exit;
                    ?>
                    <label for="pdf_adjunto" class="form-label">Seleccione el PDF adjunto,
                        actualmente: <?=empty($evento['pdf_adjunto']) ? " no tiene" : $evento['pdf_adjunto']?></label>
                    <input type="file" class="form-control" name="pdf_adjunto" id="pdf_adjunto" accept="application/pdf"
                        value=<?=$evento['pdf_adjunto']?> placeholder="Seleccione el PDF adjunto">
                </div>
            </div>
            <div class="pb-3 mb-3 row border">
                <div class="col-6">
                    <label for="desde" class="form-label">Desde (Inicio del Evento):</label>
                    <input type="date" class="form-control" name="desde" id="desde" value="<?=$evento['desde']?>"
                        placeholder="Desde (Fecha de inicio)" required>
                </div>
                <div class="col-6">
                    <label for="hasta" class="form-label">Hasta (Finalización del Evento):</label>
                    <input type="date" class="form-control" name="hasta" id="hasta" value="<?=$evento['hasta']?>"
                        placeholder="Hasta (Fecha de finalización)" required>
                </div>
            </div>
            <div class="row border pb-3 mb-3">
                <div class="mt-1 mb-3 mx-auto col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="<?=$evento['inscripcion']?>"
                            name="inscripcion" id="inscripcion" <?=$evento['inscripcion'] ? 'checked' : ''?>>
                        <label class="form-check-label" for="inscripcion">
                            Permite inscripciones
                        </label>
                    </div>
                </div>
                <div class="mt-1 mb-3 mx-auto col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="inscripcion_invitacion"
                            value="<?=$evento['inscripcion_invitacion']?>" id="inscripcion_invitacion"
                            <?=$evento['inscripcion_invitacion'] ? 'checked' : ''?>>
                        <label class="form-check-label" for="inscripcion_invitacion">
                            Permite realizar invitaciones por e-mail
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <label for="desde_inscripcion" class="form-label">Desde (Inicio del periodo de Inscripción):</label>
                    <input type="date" class="form-control" name="desde_inscripcion" id="desde_inscripcion"
                        value="<?=$evento['desde_inscripcion']?>" placeholder="Desde (Fecha de inicio)">
                </div>
                <div class="col-6">
                    <label for="hasta_inscripcion" class="form-label">Hasta (Finalización del Evento):</label>
                    <input type="date" class="form-control" name="hasta_inscripcion" id="hasta_inscripcion"
                        value="<?=$evento['hasta_inscripcion']?>" placeholder="Hasta (Fecha de finalización)">
                </div>
            </div>


            <div class="card col-4 mx-auto">
                <div class="card-header fs-5">Seleccione grupos de destinatarios</div>
                <form action="" method="post">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" name="socio" type="checkbox" value="<?=$evento['socio']?>"
                                id="socio" <?=$evento['socio'] ? 'checked' : ''?>>
                            <label class="form-check-label" for="socio">
                                Socios
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="alumno" type="checkbox" value="<?=$evento['alumno']?>"
                                id="alumno" <?=$evento['alumno'] ? 'checked' : ''?>>
                            <label class="form-check-label" for="alumno">
                                Alumnos
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="pdalumno" type="checkbox"
                                value="<?=$evento['pdalumno']?>" id="pdalumno"
                                <?=$evento['pdalumno'] ? 'checked' : ''?>>
                            <label class="form-check-label" for="pdalumno">
                                Padres o Madres de Alumnos
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="pintor" type="checkbox" value="<?=$evento['pintor']?>"
                                id="pintor" <?=$evento['pintor'] ? 'checked' : ''?>>
                            <label class="form-check-label" for="pintor">
                                Pintores o Artistas
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="dtaller" type="checkbox"
                                value="<?=$evento['dtaller']?>" id="dtaller" <?=$evento['dtaller'] ? 'checked' : ''?>>
                            <label class="form-check-label" for="dtaller">
                                Asistentes de Talleres
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="amigo" type="checkbox" value="<?=$evento['amigo']?>"
                                id="amigo" <?=$evento['amigo'] ? 'checked' : ''?>>
                            <label class="form-check-label" for="amigo">
                                Amigos o Simpatizantes
                            </label>
                        </div>
                    </div>
            </div>


            <div class="row pb-3 mt-3">
                <div class="mb-3">
                    <label for="texto" class="form-label">Texto para la Web</label>
                    <textarea class="form-control" name="texto" id="texto" rows="4"><?=$evento['texto']?></textarea>
                    <script>
                    CKEDITOR.replace("texto", {
                     language: 'es',
                     versionCheck: false
                  })
                    </script>
                </div>
                <div class="mb-3">
                    <label for="texto_carta" class="form-label">Cuerpo del texto para invitación por correo</label>
                    <textarea class="form-control" name="texto_carta" id="texto_carta"
                        rows="3"><?=$evento['texto_carta']?></textarea>
                    <script>
                    CKEDITOR.replace("texto_carta", {
                     language: 'es',
                     versionCheck: false
                  })
                    </script>
                </div>
            </div>
            <div class="row mb-3 border">
                <div class="mb-3">
                    <label for="fotos" class="form-label">Agregar Fotografías del Evento</label>
                    <input type="file" class="form-control" name="fotos[]" id="fotos" accept="image/jpeg" multiple
                        placeholder=" Seleccione las Fotografías">
                </div>
            </div>
            <div id="gf" class="row justify-content-center align-items-center">
                <?php $contenido_imagenes = $camino_cercle."imgusr/neventos/ev_" . $evento['id'];?>
                <?php $imagenes = scandir($contenido_imagenes);?>
                <?php foreach ($imagenes as $imagen): ?>
                <?php if ($imagen != "." && $imagen != ".." && $imagen != "cartel.jpg"): ?>
                <figure class="figure mb-3 border col-3 text-center mx-1 my-1 shadow-lg">
                    <img src="<?=$camino_eventos . $evento['id'] . "/" . $imagen?>" width="200"
                        class="figure-img img-fluid rounded border mx-2 my-2" alt="">
                    <figcaption class="figure-caption"><button type="submit" name="eliminar_foto"
                            value="<?=$camino_eventos . $evento['id'] . "/" . $imagen?>"
                            class="btn btn-warning btn-sm bi-trash3" title="Eliminar Imágen"></button></figcaption>
                </figure>
                <?php endif?>
                <?php endforeach;?>
            </div>
            <div class="mt-3">
                <a name="cancelar" id="cancelar" class="btn btn-success btn-md bi-box-arrow-left" href="index.php"
                    role="button" title="Cancelar"></a>
                <button type="submit" name="submit" value="<?=$evento['id']?>"
                    class="btn btn-primary btn-md bi-check-lg" title="Modificar"></button>
                <?php if ($evento['inscripcion_invitacion'] == 1 && $evento['desde_inscripcion'] <= $hoy && $evento['hasta_inscripcion'] >= $hoy): ?>
                <!--- En la siguiente linea, javascript:cuidado_accion lleva a cartear.php -->
                <!--
                              <a name="cartear" id="cartear" class="btn btn-info btn-md bi-envelope-paper" href="javascript:cuidado_accion(<?=$evento['id']?>)" role="button" title="Invitación Masiva"></a>
                              -->
                <a name="cartear" id="cartear" class="btn btn-info btn-md bi-envelope-paper"
                    href="cartear.php?evtID=<?=$evento['id']?>"
                    onclick="return confirm(¿seguro de querer enviar una invitación masiva?)" role="button"
                    title="Invitación Masiva"></a>
                <?php endif?>
            </div>
        </div>
    </form>
</div>

<?php require_once $camino_root."templates/footer.php";?>