<?php $v->layout("_theme"); ?>

<section class="blog_page">
    <header class="blog_page_header">
        <h1><?= ($title ?? 'BLOG'); ?></h1>
        <p><?= ($search ?? 'Confira nossas dicas para controlar melhor suas contas'); ?></p>
        <form name="search" action="<?= url("/blog/buscar"); ?>" method="post" enctype="multipart/form-data">
            <label>
                <input type="text" name="s" placeholder="Encontre um artigo:" required/>
                <button class="icon-search icon-notext"></button>
            </label>
        </form>
    </header>

    <?php if(empty($blog) && !empty($search)): ?>
        <div class="content content">
            <div class="empty_content">
                <h3 class="empty_content_title">Sua pesquisa não retornou resultados :/</h3>
                <p class="empty_content_desc">Você pesquisou por <strong><?= $search; ?></strong>. Tente outros termos.</p>
                <a href="<?= url("/blog"); ?>" title="Blog"
                class="empty_content_btn gradient gradient-green gradient-hover radius">... ou Volte ao blog</a>
            </div>
        </div>
    <?php elseif (empty($blog)): ?>
        <div class="content content">
            <div class="empty_content">
                <h3 class="empty_content_title">Ainda estamos trabalhando aqui!</h3>
                <p class="empty_content_desc">Nossos editores estamos preparando um conteúdo de primeira pra você</p>
            </div>
        </div>
    <?php else: ?>
        <div class="blog_content container content">
            <div class="blog_articles">
                <?php foreach ($blog as $post): ?>
                    <?php $v->insert("blog-list", ['post' => $post]); ?>
                <?php endforeach; ?>
            </div>

            <?= $paginator; ?>
        </div>
    <?php endif; ?>    
</section>