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

        for (let hit of await result.hits.hits) {
            console.log(hit);
            DomElement.show(searchResults);
            searchResultsUl.innerHTML += `
                <li class="search-result-li"><b class="me-2">${ hit._source.hymnId }</b> ${ hit._source.hymnTitle }</li>
            `;
        }
    });

    searchForm.addEventListener('mouseleave', function () {
        DomElement.hideWithTimeout(searchResults);
    });
});