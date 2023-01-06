<?php
include("topics.php");
$chat = selectAll('chat', ['iddial' => $_GET['id']]);
$d = selectOne('dialog', ['id' => $_GET['id']]);
?>
<!doctype html>
<html lang="en">
  <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel = "stylesheet" href="app/css/style.css">
    <script src="https://kit.fontawesome.com/c53200d694.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <title>TG</title>
  </head>
  <body>
      <div class="container">
          <div class="content row">
              <div class="main-content col-md-9 col-12">
                  <h2><?= $d['name']; ?></h2>
                  <div id="dialog">

                  </div>
                  <form class="chat" action="dialog.php" method="post">
                      <input type="text" id="message" autofocus autocomplete="off"/>
                      <input name="msg-btn" class="btn" type="submit" value="Отправить" id="sendMail"/>
                  </form>
              </div>
              <div class="sidebar col-md-3 col-12">
                  <a role="button" href="add.php" class="btn btn-outline-success">Добавить беседу</a>
                  <div class="section topics">
                      <h3>Беседы</h3>
                      <ul>
                          <?php foreach ($messages as $key => $msg): ?>
                              <li><a href="<?="dialog.php?id=" . $msg['id']; ?>"><?= $msg['name']; ?></a></li>
                          <?php endforeach; ?>
                      </ul>
                  </div>
                  <a role="button" href="index.php" class="btn btn-outline-danger">Выйти из диалога</a>
              </div>
          </div>
      </div>

     <script>
     var start = 0, url = 'http://tg/chat.php';
     // вычленяю id страницы
     var ssilk = document.location.search;
     var searchParams = new URLSearchParams(ssilk);
     var mydial = searchParams.get("id");
     load();
     $(document).ready(function(){
         var login = prompt("Введи свое имя:");
         $("#sendMail").on("click", function(){
             var message = $("#message").val().trim();
             $.ajax({
                 url: 'chat.php',
                 type: 'POST',
                 cache: false,
                 data: {'login': login, 'message': message, 'ssilk': mydial},
                 dataType: 'html',
                 success: function(data){
                     $('#message').val('');
                 }
             })
             return false;
         });
     });

     function load(){
         $.get(url + '?start=' + start, function(res){
             if(res.items){
                 res.items.forEach(item =>{
                     start = item.id;
                     if (item.iddial == mydial){
                        $('#dialog').append(renderMessage(item));
                     }
                 })
             };
             load();
         });
     }

     function renderMessage(item){
         var time = new Date(item.time);
         time = `${time.getHours()}:${time.getMinutes() < 10 ? '0' : ''}${time.getMinutes()}`;
         return `<div class="msg"><p>${item.login} <span>${time}</span></p>${item.message}</div>`;
     }
     </script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
