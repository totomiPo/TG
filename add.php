<?php
include("topics.php");
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel = "stylesheet" href="app/css/style.css">
    <script src="https://kit.fontawesome.com/c53200d694.js" crossorigin="anonymous"></script>
    <title>TG</title>
  </head>
  <body>
      <div class="container">
          <div class="content row">
              <div class="main-content col-md-9 col-12">
                  <h2>Название диалога</h2>
                  <div class="err col-12">
                      <?php if (is_countable($err) && count($err) > 0): ?>
                          <ul>
                              <?php foreach ($err as $error): ?>
                                  <li><?= $error; ?></li>
                              <?php endforeach; ?>
                          </ul>
                      <?php endif; ?>
                  </div>
                  <form action="add.php" method="post">
                      <input type="text" name="name" id="message" autofocus autocomplete="off"/><br />
                      <button type="submit" name="topcr" class="btn btn-outline-primary mt-3">Создать</button>
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
              </div>
          </div>
      </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
