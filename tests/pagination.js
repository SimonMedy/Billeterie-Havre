function setupPagination(cards, wrapper, cardsPerPage) {
    wrapper.innerHTML = "";
    
    let pageCount = Math.ceil(cards.length / cardsPerPage);
    for (let i = 1; i <= pageCount; i++) {
        let btn = paginationButton(i, cards);
        wrapper.appendChild(btn);
    }
}

function paginationButton(page, cards) {
    let button = document.createElement('button');
    button.innerText = page;
    button.className = 'page-button';
    button.addEventListener('click', () => displayCards(page, cards));

    return button;
}

function displayCards(page, cards, cardsPerPage) {
    let start = (page - 1) * cardsPerPage;
    let end = start + cardsPerPage;
    cards.forEach((card, index) => {
        card.style.display = index >= start && index < end ? 'block' : 'none';
    });
}


module.exports = { setupPagination, paginationButton, displayCards };

document.addEventListener('DOMContentLoaded', function() {
    const cardsPerPage = 6;
    const cards = document.querySelectorAll('.card-element');
    const paginationElement = document.querySelector('.pagination');
    displayCards(1, cards, cardsPerPage); 
    setupPagination(cards, paginationElement, cardsPerPage);
});
