{extends file="layouts/app.tpl"}

{block name="content"}
    <main>
        <section>
            <div class="container">
                <img src="{$article->getImageUrl()}" alt="" class="page_banner">

                <header class="page_header">
                    <div class="page_header__info">
                        <h1 class="page_title">{$article->getName()}</h1>
                        <p class="page_caption">{$article->getCreatedAt()->format('F j Y')}</p>
                        <div class="page_caption">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" width="15" height="15">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            <span>{$article->getViewsCount()}</span>
                        </div>
                        <p>{$article->getDescription()}</p>
                    </div>
                </header>

                <p class="article_content">{$article->getContent()}</p>

                <div class="listing">
                    <ul class="listing__items">
                        {foreach $relatedArticles as $article}
                            {include file="components/card.tpl" href="/article/{$article->getId()}" imageUrl=$article->getImageUrl() title=$article->getName() time=$article->getCreatedAt()->format('F j Y') description=$article->getDescription()}
                        {/foreach}
                    </ul>
                </div>
            </div>
        </section>
    </main>
{/block}