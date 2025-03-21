<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2025國際交響樂團演奏會(ISOC)</title>
    <link rel="icon" href="./img/logo.png">
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header id="navbar">
        <h2 id="site-title">2025國際交響樂團演奏會(ISOC)</h2>
        <img src="./img/logo.png" alt="logo" id="logo">
        <a href="home.html" id="home">Home</a>
        <a href="news.html" id="news">News</a>
        <a href="performance.html" id="performance">Performance</a>
        <a href="tickets.html" id="tickets">Tickets</a>
        <a href="search.html" id="search">Search</a>
        <a href="admin.php" id="admin">Admin</a>
    </header>
    <main>
        <div>
            <table id="resulttable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Password</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ticket-table-body">
                <?php
                    include "db.php";
                    
                    $searchItem = $_GET['SearchItem'];
                    $item = "%" . $_GET['Item'] . "%";

                    $sql = "SELECT * FROM tickets WHERE $searchItem LIKE :item";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':item', $item, PDO::PARAM_STR);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr id='row-{$row['id']}'>";
                            echo "<td>{$row['id']}</td>";
                            echo "<td>{$row['firstname']}</td>";
                            echo "<td>{$row['lastname']}</td>";
                            echo "<td>{$row['phone']}</td>";
                            echo "<td>{$row['password']}</td>";
                            echo "<td>
                                <button class='btn' onclick='del({$row['id']})'>刪除</button>
                                <button class='btn edit' data-row='".json_encode($row)."'>編輯</button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>沒搜尋到任何項目</td></tr>";
                    }
                ?>
                </tbody>
            </table>
            <button id="back-button" class="btn w-100" style="font-size: 1.5em;" onclick="location.href = 'search.html';">重新查詢</button>
        </div>
    </main>
    <div class="modal fade" id="edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                <label for="firstname">First name</label>
                <input class="form-control" type="text" id="firstname" name="firstname"><br>
                <label for="lastname">Last name</label>
                <input class="form-control" type="text" id="lastname" name="lastname"><br>
                <label for="phone">Phone</label>
                <input class="form-control" type="text" id="phone" name="phone"><br>
                <label for="Password">Password</label>
                <input class="form-control" type="text" id="password" name="password"><br>
                </div>
                <div class="modal-footer">
                    <button class="btn" onclick="edit()">編輯</button>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div id="share-info">
            <img src="./img/facebook.png" alt="fb" id="fb">
            <img src="./img/google.png" alt="google" id="google">
        </div>
        <span id="footer">Copyright &copy; 2025 ISOC All Rights Reserved</span>
    </footer>
    <script src="./js/jquery.js"></script>
    <script src="./js/jquery-ui.min.js"></script>
    <script src="./js/bootstrap.js"></script>
    <script>
    function del(id) {
        if (confirm("確定要刪除這筆資料嗎？")) {
            $.post("del.php",{id:id},function () {
            }).done(function () {
                alert("資料已刪除")
                // 如果你沒有想要追求極致的UX的話我建議你直接把頁面刷新就好
                location.reload()
            }).fail(function () {
                alert("刪除錯誤 稍等一下")
            })
        }
    };
    let row
    $("button.edit").click(function () {
        console.log(row);
        
        row = $(this).data('row')
        $("#firstname").val(row.firstname)
        $("#lastname").val(row.lastname)
        $("#phone").val(row.phone)
        $("#password").val(row.password)
        $("div.modal#edit").modal("show")
    })
    function edit() {
        let form = {
            id: row.id,
            firstname: $("#firstname").val(),
            lastname: $("#lastname").val(),
            phone: $("#phone").val(),
            password: $("#password").val(),
        }
        $.post("edit.php",form,function () {
        }).done(function () {
            alert("資料已修改")
            location.href = "search.html";
        }).fail(function () {
            alert("修改錯誤")
        })
    }
    </script>
</body>
</html>