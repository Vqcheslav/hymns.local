document.addEventListener('DOMContentLoaded', function () {

});

window.addEventListener('load', function () {
    let searchForm = document.getElementById('search-form');
    let searchResults = document.getElementById('search-results');
    let searchResultsUl = document.getElementById('search-results-ul');

    searchForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        let query = document.getElementById('search-input').value;
        let result = await Server.getData('/api/hymns/search/' + query);

        console.log(result);
        searchResultsUl.innerHTML = '';

        let hits = await result.hits.hits;
        DomElement.show(searchResults);

        for (let hit of hits) {
            console.log(hit);
            searchResultsUl.innerHTML += `
                <li class="search-result-li">
                    <a class="search-link" href="#${ hit._source.hymnId }" data-hymn-id="${ hit._source.hymnId }">
                        <div class="d-flex mb-2">
                            <b class="me-2">${ hit._source.hymnId }</b>
                            ${ hit._source.hymnTitle }
                        </div>
                        <p class="search-result-couplet">${ hit._source.couplet }</p>
                    </a>
                </li>
            `;
        }

        if (hits.length === 0) {
            searchResultsUl.innerHTML = 'К сожалению, ничего не найдено';
        }

        document.querySelectorAll('.search-link').forEach(function (element) {
            element.addEventListener('click', function () {
                let hymn = document.getElementById(this.dataset.hymnId);
                hymn.classList.add('outline');

                setTimeout(() => hymn.classList.remove('outline'), 10000);
            });
        })
    });

    searchForm.addEventListener('mouseleave', function () {
        DomElement.hideWithTimeout(searchResults);
    });
});