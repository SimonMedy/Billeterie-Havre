const { displayCards } = require('./pagination');

test('should display only the specified number of cards per page', () => {
  document.body.innerHTML = `
    <div class="card-element" style="display: none;"></div>
    <div class="card-element" style="display: none;"></div>
    <div class="card-element" style="display: none;"></div>
    <div class="card-element" style="display: none;"></div>
    <div class="card-element" style="display: none;"></div>
    <div class="card-element" style="display: none;"></div>
  `;

  const cards = document.querySelectorAll('.card-element');
  displayCards(1, cards, 3);

  expect(cards[0].style.display).toBe('block');
  expect(cards[1].style.display).toBe('block');
  expect(cards[2].style.display).toBe('block');
  expect(cards[3].style.display).toBe('none');
  expect(cards[4].style.display).toBe('none');
  expect(cards[5].style.display).toBe('none');
});
