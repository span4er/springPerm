<!DOCTYPE html>
<html>
<head>
 <title>Личный кабинет</title>
 <style>
  body {
   background-color: #f3f3f3;
   font-family: Arial, sans-serif;
   margin: 0;
   padding: 0;
  }

  h1 {
   text-align: center;
   margin-top: 20px;
  }

  div {
   background-color: white;
   border-radius: 5px;
   box-shadow: 0 0 5px rgba(0,0,0,.2);;
   margin: 20px auto;
   max-width: 500px;
   padding: 20px;
  }

  img {
   border-radius: 50%;
   display: block;
   margin: 0 auto 20px;
   max-width: 100px;
  }

  p span {
   font-weight: bold;
  }
 </style>
</head>
<body>
 <h1>Личный кабинет</h1>
 <div>
  <img src="avatar.jpg" alt="Аватар">
  <p>Имя пользователя: <span>Иван</span></p>
  <p>Электронная почта: <span>ivan@mail.ru</span></p>
  
  <form action="#" method="post">
   <label for="current-password">Текущий пароль:</label>
   <input type="password" name="current-password" id="current-password" required>
   <label for="new-password">Новый пароль:</label>
   <input type="password" name="new-password" id="new-password" required>
   <label for="new-password-repeat">Повторите новый пароль:</label>
   <input type="password" name="new-password-repeat" id="new-password-repeat" required>
   <input type="submit" value="Изменить пароль">
  </form>
 </div>
</body>
</html>