// Ładowanie header i footer na każdej stronie
function loadComponent(id, url) {
    fetch(url)
        .then(res => res.text())
        .then(html => { document.getElementById(id).innerHTML = html; })
        .then(() => { if(id === 'header') initMenuBar(); });
}

function initMenuBar() {
    const coin = document.getElementById('coinMenu');
    const dropdownMenu = document.getElementById('dropdownMenu');
    let coinFlipped = false;
    if(coin && dropdownMenu) {
        coin.addEventListener('click', function(e) {
            e.stopPropagation();
            coinFlipped = !coinFlipped;
            if(coinFlipped) {
                coin.style.transform = 'rotateY(180deg)';
                dropdownMenu.classList.add('show');
            } else {
                coin.style.transform = 'rotateY(0deg)';
                dropdownMenu.classList.remove('show');
            }
        });
        dropdownMenu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                dropdownMenu.classList.remove('show');
                coin.style.transform = 'rotateY(0deg)';
                coinFlipped = false;
            });
        });
        document.addEventListener('click', function(e) {
            if(coinFlipped && !dropdownMenu.contains(e.target) && e.target !== coin) {
                dropdownMenu.classList.remove('show');
                coin.style.transform = 'rotateY(0deg)';
                coinFlipped = false;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('header')) loadComponent('header', 'header.html');
    if(document.getElementById('footer')) loadComponent('footer', 'footer.html');
}); 