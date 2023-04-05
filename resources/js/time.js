// // <script type = "text/javascript">
    function getCurrDate () {
    var monthList = new Array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля',
    'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря');

    var dayList = new Array("Воскресенье",'Понедельник', "Вторник", "Среда", "Четверг", "Пятница", "Суббота");

    var curr_date = new Date();
    var curr_year = curr_date.getFullYear();
    var curr_month = monthList[curr_date.getMonth()];
    var curr_day = dayList[curr_date.getDay() ];
    var curr_seconds = curr_date.getSeconds().toString();
    var curr_minutes = curr_date.getMinutes().toString();
    if(curr_minutes.length == 1) curr_minutes= "0" + curr_minutes;
    if(curr_seconds.length == 1) curr_seconds = "0" + curr_seconds;

    document.getElementById("current_date_time").innerText = curr_year + " " +  curr_day +" " + curr_date.getDate() + " " + curr_month + " " + curr_date.getHours() + ":" + curr_minutes +":" + curr_seconds;
}
    setInterval(getCurrDate,1000);
