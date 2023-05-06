ymaps.ready(init);
var myMap;

function init () {
    myMap = new ymaps.Map("map", {
        center: [58.0052, 56.2376], // Углич
        zoom: 11
    }, {
        balloonMaxWidth: 200,
        searchControlProvider: 'yandex#search'
    });

    // Обработка события, возникающего при щелчке
    // левой кнопкой мыши в любой точке карты.
    // При возникновении такого события откроем балун.
    myMap.events.add('click', function (e) {
        if (!myMap.balloon.isOpen()) {
            var coords = e.get('coords');
            let element_long = document.getElementById('coordinates_long');
            let element_latit = document.getElementById('coordinates_latitud');
            element_long.value = coords[0].toPrecision(6);
            element_latit.value = coords[1].toPrecision(6);

            myMap.balloon.open(coords, {
                contentHeader:'Расположение родника',
                contentBody:'<p>Выбрана точка родника</p>' +
                    '<p>Координаты родника: ' + [
                    coords[0].toPrecision(6),
                    coords[1].toPrecision(6)
                    ].join(', ') + '</p>',
            });
        }
        else {
            myMap.balloon.close();
        }
    });

    // Обработка события, возникающего при щелчке
    // правой кнопки мыши в любой точке карты.
    // При возникновении такого события покажем всплывающую подсказку
    // в точке щелчка.
    myMap.events.add('contextmenu', function (e) {
        myMap.hint.open(e.get('coords'), 'Кто-то щелкнул правой кнопкой');
    });
    
    // Скрываем хинт при открытии балуна.
    myMap.events.add('balloonopen', function (e) {
        myMap.hint.close();
    });
}