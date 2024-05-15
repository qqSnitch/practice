<?php 
   $host = 'localhost'; // адрес сервера
   $db_name = 'u67277'; // имя базы данных
   $user = 'u67277'; // имя пользователя
   $password = '7133721'; // пароль

   // создание подключения к базе   
      $connection = mysqli_connect($host, $user, $password, $db_name);
      $opt = $_POST['options'];
      
     

      if ($opt=="1") {
        
        // текст SQL запроса, который будет передан базе
        $query = "SELECT * FROM publication WHERE type_public = 'Газета' AND name_public LIKE 'П%'";
        
         // выполняем запрос к базе данных
        $result = mysqli_query($connection, $query);

        // выводим полученные данные
        while($row = $result->fetch_assoc()){
          echo  $row['id_public'] . ' - ' . $row['index_public'] . ' - ' . $row['type_public'] . ' - ' . $row['name_public'] . ' - ' . $row['price_public'] . "<br>";
        }
      }
      if ($opt=="2") {
        // текст SQL запроса, который будет передан базе
        $query = "SELECT r.name_rec, r.street_rec, r.num_home_rec, r.num_flat_rec, p.name_public
        FROM recipients r
        JOIN delivery d ON r.id_recipients = d.id_recipients
        JOIN publication p ON d.id_public = p.id_public
        WHERE r.street_rec = 'Красная' AND p.index_public = '13123'";
        
         // выполняем запрос к базе данных
        $result = mysqli_query($connection, $query);

        // выводим полученные данные
        while($row = $result->fetch_assoc()){
          echo  $row['name_rec'] . ' - ' . $row['street_rec'] . ' - ' . $row['num_home_rec'] . ' - ' . $row['num_flat_rec'] . "<br>";
        }
      }
      if ($opt==3) {
        // текст SQL запроса, который будет передан базе
        $query = "SELECT * FROM recipients
        WHERE street_rec = 'Красная' AND num_home_rec IN (15, 10, 33)";
        
         // выполняем запрос к базе данных
        $result = mysqli_query($connection, $query);

        // выводим полученные данные
        while($row = $result->fetch_assoc()){
          echo  $row['id_recipients'] . ' - ' . $row['name_rec'] . ' - ' . $row['street_rec'] . ' - ' . $row['num_home_rec'] . ' - ' . $row['num_flat_rec'] . $row['id_public'] . $row['period_rec'] . "<br>";
        }
      }
      if ($opt==4) {
        if (isset($_POST['index'])) {
          $index = $_POST['index']; // Получаем значение индекса издания из формы
      
          // Запрос к базе данных для получения информации об издании с заданным индексом
          $query = "SELECT * FROM publication WHERE index_public = ?";
          $stmt = $connection->prepare($query);
          $stmt->bind_param("i", $index); // Привязываем значение индекса к параметру запроса
          $stmt->execute();
          $result = $stmt->get_result();
          // Выводим информацию об издании в виде таблицы
          if (!empty($result)) {
            while($row = $result->fetch_assoc()){
              echo  $row['id_public'] . ' - ' . $row['index_public'] . ' - ' . $row['type_public'] . ' - ' . $row['name_public'] . ' - ' . $row['price_public'] . "<br>";
            }
          } else {
              echo "No publication found with this index.";
          }
      
          $stmt->close();
        }
      }
      if ($opt==5) {
        if (isset($_POST['rangelow'])&&isset($_POST['rangehight'])) {
          $low = $_POST['rangelow'];
          $hight = $_POST['rangehight'];
      
          // Запрос к базе данных для получения информации об издании с заданным индексом
          $query = "SELECT * FROM publication WHERE price_public BETWEEN ? AND ?";
          $stmt = $connection->prepare($query);
          $stmt->bind_param("ii", $low,$hight); // Привязываем значение индекса к параметру запроса
          $stmt->execute();
          $result = $stmt->get_result();
          // Выводим информацию об издании в виде таблицы
          if (!empty($result)) {
            while($row = $result->fetch_assoc()){
              echo  $row['id_public'] . ' - ' . $row['index_public'] . ' - ' . $row['type_public'] . ' - ' . $row['name_public'] . ' - ' . $row['price_public'] . "<br>";
            }
          } else {
              echo "No publication found with this index.";
          }
      
          $stmt->close();
        }
      }
      if ($opt==6) {
        // текст SQL запроса, который будет передан базе
        $query = "SELECT 
        p.index_public, 
        p.name_public, 
        p.price_public, 
        r.period_rec, 
        d.del_date,
        p.price_public * r.period_rec AS cost
    FROM 
        recipients r
    JOIN 
        publication p ON r.id_public = p.id_public
    JOIN
        delivery d ON d.id_public=p.id_public";
        
         // выполняем запрос к базе данных
        $result = mysqli_query($connection, $query);

        // выводим полученные данные
        while($row = $result->fetch_assoc()){
          echo  $row['index_public'] . ' - ' . $row['name_public'] . ' - ' . $row['price_public'] . ' - ' . $row['period_rec'] . ' - ' . $row['del_date'] . ' - ' . $row['cost']  . "<br>";
        }
      }
      if ($opt==7) {
       // текст SQL запроса, который будет передан базе
       $query = "SELECT 
       type_public, 
       AVG(price_public) AS avg
   FROM 
       publication
   GROUP BY 
       type_public";
        
       // выполняем запрос к базе данных
      $result = mysqli_query($connection, $query);

      // выводим полученные данные
      while($row = $result->fetch_assoc()){
        echo  $row['type_public'] . ' - ' . $row['avg'] . "<br>";
      }
      }
      if ($opt==8) {
       // текст SQL запроса, который будет передан базе
       $query = "SELECT 
       street_rec, 
       COUNT(id_recipients) AS count
   FROM 
       recipients
   GROUP BY 
       street_rec";
        
       // выполняем запрос к базе данных
      $result = mysqli_query($connection, $query);

      // выводим полученные данные
      while($row = $result->fetch_assoc()){
        echo $row['street_rec'] . ' - ' . $row['count'] . "<br>";
      }
      }
      // закрываем соединение с базой
      mysqli_close($connection);
      ?>