{extends file="layouts/app.tpl"}

{block name="content"}
    <main>
        <section>
            <div class="container">
                <header class="page_header">
                    <div class="page_header__info">
                        <h1 class="page_title">{$category->getName()}</h1>
                        <p>{$category->getDescription()}</p>
                    </div>

                    <div class="page_header__menu">
                        <label for="sort">Сортировка</label>
                        <select id="sort" class="form_select">
                            <option {if $pagination['sort'] === 'views'}selected{/if} value="views">По количеству просмотров</option>
                            <option {if $pagination['sort'] === 'publish'}selected{/if} value="publish">По дате публикации</option>
                        </select>
                    </div>
                </header>

                <div class="listing">
                    <ul class="listing__items">
                        {foreach $articles as $article}
                            {include file="components/card.tpl" href="/article/{$article->getId()}" imageUrl=$article->getImageUrl() title=$article->getName() time=$article->getCreatedAt()->format('F j Y') description=$article->getDescription()}
                        {/foreach}
                    </ul>
                </div>

                {if $pagination['total'] > 1}
                    <nav class="pagination_container">
                        <ul class="pagination">
                            <li class="pagination__item {if $pagination['current'] <= 1}is-disabled{/if}">
                                <a href="?page={$pagination['current'] - 1}&sort={$pagination['sort']}"
                                   class="pagination__link">&laquo;</a>
                            </li>

                            {for $p=1 to $pagination['total']}
                                <li class="pagination__item {if $p === $pagination['current']}is-active{/if}">
                                    <a href="?page={$p}&sort={$pagination['sort']}" class="pagination__link">{$p}</a>
                                </li>
                            {/for}

                            <li class="pagination__item {if $pagination['current'] >= $pagination['total']}is-disabled{/if}">
                                <a href="?page={$pagination['current'] + 1}&sort={$pagination['sort']}"
                                   class="pagination__link">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                {/if}
            </div>
        </section>
    </main>

    {*  TODO: Можно вынести в отдельный скрипт файл, но для упрощения пишим здесь  *}
    <script>
        document.querySelector('#sort').addEventListener('change', e => {
            const url = new URL(window.location.href)
            url.searchParams.delete('page')
            url.searchParams.set('sort', e.target.value)
            window.location.href = url.toString()
        })
    </script>
{/block}