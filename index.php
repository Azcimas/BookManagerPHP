<!DOCTYPE html>

<html>
    <link href="styles.css" rel="stylesheet" type="text/css" />
    <head>
        <meta charset="UTF-8">
        <title>Book's List</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="jquery.multi-select.js"></script>
        <script type="text/javascript" src="submit.js"></script>
        
    </head>
    <body>
        <div class="content"><img src="logo.jpg" border="0" /></div>
        <div class="content">
        
            <h1> LISTA KSIĄŻEK </h1>
        <div class="content_wrapper">
        <table id="responds">
            <tr class="top">
                <th>Usuń</th>
                <th>Edycja</th>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Rok</th>
                <th>Autorzy</th>
            </tr>
        <?php 
        $db = new PDO('mysql:host=localhost;dbname=books;charset=utf8', 'root', 'azcim1479');
        $select = 'SELECT b.id, Title, Year, GROUP_CONCAT(DISTINCT Name ORDER BY Name) as con_list
                   FROM authors a, books b, authors_to_books c
                   WHERE a.id = c.author_id AND b.id = c.book_id
                   GROUP BY b.id
                   ORDER BY b.id;';
        foreach($db->query($select) as $row) {?>
            <tr id="item_<?=$row["id"]?>">
            <td><div class="del_wrapper" align="center">
            <a href="#" class="del_button" id="del-<?=$row["id"]?>">
            <img src="icon_del.gif" border="0" /></a></div></td>
            <td><div class="del_wrapper" align="center">
            <a href="#" class="edit_button" id="del-<?=$row["id"]?>">
            <img src="editicon.png" border="0" /></a></div></td>
            <td><?=$row['id']?>.</td>
            <td><?=$row['Title']?></td>
            <td><?=$row['Year']?></td>
            <td><?=$row['con_list']?></td></tr>
        <?php }?>
        </table>
        
        <br>
        <textarea name="content_txt" id="contentText" cols="35" rows="1" placeholder="Wpisz nazwę książki"></textarea><br>
        <textarea name="year_txt" id="yearText" cols="35" rows="1" placeholder="Wpisz datę wydania"></textarea><br>
        Nowi autorzy(dodawanie):<br>
        <textarea name="authors_txt" id="authorText" cols="35" rows="1" placeholder="Wpisz autorów"></textarea><br>
        <br>
        Autorzy z bazy:
        <select multiple="multiple" id="my-select" name="my-select[]">
        <?php 
        $select2 = 'SELECT * FROM authors';
        foreach($db->query($select2) as $row2) {?> 
           <option value="elem_<?=$row2["id"]?>"><?=$row2['Name']?></option>
                    
         <?php }?>   
        </select>

        <button id="FormSubmit">Dodaj Książkę</button>
        
        <br>
        
        </div>
        <h1> LISTA AUTORÓW </h1>
        <div class="authors">
        <br>
        <table id="authors">
            <tr class="top">
                <th>Usuń</th>
                <th>Edycja</th>
                <th>ID</th>
                <th>Autor</th>
                
            </tr>
        <?php 
        
        
        foreach($db->query($select2) as $row2) {?>
            <tr id="item_<?=$row2["id"]?>">
            <td><div class="del_wrapper" align="center">
            <a href="#" class="del_button" id="del-<?=$row2["id"]?>">
            <img src="icon_del.gif" border="0" /></a></div></td>
            <td><div class="del_wrapper" align="center">
            <a href="#" class="edit_button" id="del-<?=$row2["id"]?>">
            <img src="editicon.png" border="0" /></a></div></td>
            <td><?=$row2['id']?>.</td>
            <td><?=$row2['Name']?></td>
            
        <?php }?>
        </table>
        <br>
        
        <textarea name="authors_txt2" id="authorText2" cols="35" rows="1" placeholder="Wpisz autora"></textarea><br>
        <button id="FormSubmit2">Dodaj Autora</button>

        </div>
        </div>
    </body>
</html>