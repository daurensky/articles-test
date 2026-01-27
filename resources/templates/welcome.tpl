{extends file="layouts/app.tpl"}

{block name="content"}
    <main>
        <section>
            <div class="container">
                {foreach $categories as $category}
                    <div class="listing">
                        <header class="listing__header">
                            <h2 class="listing__title">{$category->getName()}</h2>
                            <a href="/category/{$category->getId()}" class="listing__action">View All</a>
                        </header>

                        <ul class="listing__items">
                            {foreach $category->getLatestArticles() as $article}
                                {include file="components/card.tpl" href="/article/{$article->getId()}" imageUrl=$article->getImageUrl() title=$article->getName() time=$article->getCreatedAt()->format('F j Y') description=$article->getDescription()}
                            {/foreach}
                        </ul>
                    </div>
                {/foreach}
            </div>
        </section>
    </main>
{/block}