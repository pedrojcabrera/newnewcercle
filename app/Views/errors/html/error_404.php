<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Página no encontrada | Cercle d'Art de Foios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('recursos/style.css') ?>">
    <style>
        body {
            background: #fafafa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .error-404-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            gap: 2rem;
        }
        .error-404-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 0.75rem;
            padding: 3rem 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
        }
        .logo-404-wrap {
            text-align: center;
            width: 100%;
        }
        .logo-404-wrap img {
            width: clamp(160px, 50vw, 480px);
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .error-404-card .code {
            font-size: 5rem;
            font-weight: 700;
            color: #222;
            line-height: 1;
            letter-spacing: -2px;
        }
        .error-404-card .titulo {
            font-size: 1.3rem;
            color: #444;
            margin: 0.5rem 0 1rem;
        }
        .error-404-card p {
            color: #777;
            font-size: 0.97rem;
            margin-bottom: 1.75rem;
        }
        .error-404-card a.btn-home {
            display: inline-block;
            padding: 0.6rem 2rem;
            background: #c0392b;
            color: #fff;
            border-radius: 0.4rem;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background .2s;
        }
        .error-404-card a.btn-home:hover {
            background: #a93226;
        }
        footer.error-footer {
            text-align: center;
            padding: 1.2rem;
            font-size: 0.82rem;
            color: #aaa;
            border-top: 1px solid #efefef;
        }
    </style>
</head>

<body>
    <div class="error-404-wrap">
        <div class="logo-404-wrap">
            <img
                src="<?= base_url('recursos/imagenes/anagramaColor.png') ?>"
                alt="Logo Cercle d'Art de Foios">
        </div>
        <div class="error-404-card">

            <div class="code">404</div>
            <div class="titulo">Página no encontrada</div>

            <p>Lo sentimos, la página que busca no existe o ha sido movida.</p>

            <a href="<?= base_url('/') ?>" class="btn-home">Volver al inicio</a>
        </div>
    </div>

    <footer class="error-footer">
        &copy; <?= date('Y') ?> Cercle d'Art de Foios
    </footer>
</body>

</html>
