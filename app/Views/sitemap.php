<?= '<?xml version="1.0" encoding="UTF-8"?>' . "\n" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc><?= base_url('/') ?></loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?= base_url('ultimos_eventos') ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= base_url('eventos') ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= base_url('galerias') ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= base_url('contactar') ?></loc>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?= base_url('privacidad') ?></loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <?php foreach ($eventos as $evento): ?>
    <url>
        <loc><?= base_url('eventos/' . $evento->id) ?></loc>
        <lastmod><?= esc(substr($evento->hasta, 0, 10)) ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>

    <?php foreach ($artistas as $artista): ?>
    <url>
        <loc><?= base_url('galerias/' . $artista->id_user) ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>

</urlset>
