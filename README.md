Технология AJAX
========================

Задание
------------------------

Разработать и реализовать анонимный чат с возможностью создания каналов. В интерфейсе отображается список каналов, пользователь может либо подключится к существующему каналу, либо создать новый.Сообщения достовляются пользователю без обновления страницы.

Ход работы
------------------------

- Спроектировать пользовательский интерфейс
- Описать пользовательские сценарии работы
- Описать API сервера и хореографию
- Описать структуру базы данных
- Описать алгоритмы

#### [1. Пользовательский интерфейс](https://www.figma.com/proto/JlXefuF6he1WsPkzj4dV3a/Untitled?node-id=1%3A2&scaling=min-zoom&page-id=0%3A1)

![Интерфейс](https://github.com/totomiPo/TG/blob/main/Интерфейс.png)

#### 2. Пользовательский сценарий работы
Первоначально пользователь попадает на главную страницу **index.php**.  
  
Пользователь может создать канал, нажав на кнопку *Добавить канал* и перейдя на страницу **add.php**. Перейдя на форму создания канала, пользователь должен ввести название и отправить запрос на сервер. Если название не удовлетворяет требованиям, пользователь должен повторить ввод, иначе перенаправится на созданную страницу с чатом **dialog.php?id=**. 
  
Пользователь также может выбрать существующий канал, который представлен в сайд-баре. Также у пользователя есть возможность покинуть канал, нажав на кнопку *Покинуть канал*.  
  
При входе в канал, выходит форма, в которой нужно заполнить свой логин. Далее пользователю доступен чат, в котором отображаются все входящие и исходящие сообщения.  

#### 3. API сервера и хореография
![Хореография](https://github.com/totomiPo/TG/blob/main/Хореография.png)

#### 4. Структура базы данных

*Таблица chat*
| Название | Тип | Длина | NULL | Описание |
| :------: | :------: | :------: | :------: | :------: |
| **id** | INT  | 11 | NO | Автоматический идентификатор сообщения |
| **message** | TEXT |  | NO | Сообщение пользователя |
| **login** | VARCHAR | 255 | NO | Логин пользователя |
| **time** | TIMESTAMP |  | CURRENT_TIMESTAMP | Дата создания сообщения |
| **iddial** | INT | 11 | NO | ID диалога |

*Таблица dialog*
| Название | Тип | Длина | NULL | Описание |
| :------: | :------: | :------: | :------: | :------: |
| **id** | INT  | 11 | NO | Автоматический идентификатор диалога |
| **name** | VARCHAR | 255 | NO | Название диалога |

#### 5. Алгоритм
_Создание диалога_  
![Создание](https://github.com/totomiPo/TG/blob/main/Создание%20канала.jpg)    
  
_Общение в чате_  
![Общение](https://github.com/totomiPo/TG/blob/main/Общение.jpg)  

#### 6. Значимые фрагменты кода
*Отправка сообщения*
```php
$res = array();
$login = isset($_POST['login']) ? trim($_POST['login']) : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : null;
$ssilk = (int)isset($_POST['ssilk']) ? $_POST['ssilk'] : null;

if(!empty($message) || !empty($login)){
    $sql = "INSERT INTO `chat` (`message`, `login`, `iddial`) VALUES ('".$message."', '".$login."', '$ssilk')";
    $res['status'] = $db->query($sql);
}

```

*Получение сообщения из бд*
```php
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$items = $db->query("SELECT * FROM `chat` WHERE `id` > " .$start);
while ($row = $items->fetch_assoc()) {
    //$row - ассоциативный массив, соответствующий ряду
    $res['items'][] = $row;
}
echo json_encode($res);
$db->close();
```

*Добавление канала*
```php
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topcr'])){
    $name = trim($_POST['name']);
    if($name === ''){
        array_push($err, "Поле не заполнено!");
    }elseif (mb_strlen($name, 'UTF8') < 3){
        array_push($err, "Название должно быть больше 2-ух символов!");
    }else{
        $row = selectOne('dialog', ['name' => $name]);
        if(!empty($row['name']) && $row['name'] === $name){
            array_push($err, "Такой диалог существует!");
        }else{
            $dialog = [
                'name' => $name
            ];
            $id = insert('dialog', $dialog);
            $info = selectOne('dialog', ['id' => $id]);
            header('location: http://tg/dialog.php?id=' . $id);
        }
    }
}else{
    $name = '';
}
```

*AJAX*
```js
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
```


Вывод
------------------------
В ходе выполнения лабораторной работы спроектировали и разработали систему анонимного общения пользователей с динамическим (без обновления страницы) отображением сообщений в чате.
