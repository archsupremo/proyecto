<?php template_set('title', 'Error') ?>
<section class="row" id="error">
    <article class="large-6 large-centered columns">
        <img src="/img/nada.jpg" alt="" />
    </article>
    <article class="large-2 large-centered columns">
        <p>
            No hay nada por aqui >.<
        </p>
    </article>
    <article class="large-2 large-centered columns">
        <?= anchor('/frontend/portada', 'Ver productos',
                   'class="success button small radius"') ?>
    </article>
</section>
