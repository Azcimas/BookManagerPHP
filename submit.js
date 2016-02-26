$(document).ready(function () {
    // add Books
    $('#my-select').multiSelect();
    $("#FormSubmit").click(function (e) {
        e.preventDefault();
        
        if ($("#contentText").val() === '' || $("#yearText").val() === '') {
            alert("Wpisz wszystkie wartosci!");
            return false;
        }
        if (!$.isNumeric($('#yearText').val()) || $("#yearText").val().length !== 4
                || $("#yearText").val() < 1 || Math.round($("#yearText").val()) === $("#yearText").val()) {
            alert("JS Error");
            $("#contentText").val('');
            $("#yearText").val('');
            $("#authorText").val('');
            return false;
        }
        



        var foo = [];
        $('#my-select :selected').each(function(i, selected){
            foo[i] = $(selected).text();
        });
        console.log(foo);
        var arr = {
            content_txt: $("#contentText").val(),
            year_txt: $("#yearText").val(),
            author_txt: $("#authorText").val(),
            author_txt_typed: foo
        };

        jQuery.ajax({
            type: "POST",
            url: "response.php",
            dataType: "JSON",
            data: arr,
            success: function (response) {

                if (response["Error"]) {
                    alert(response["Error"]);
                    $("#contentText").val('');
                    $("#yearText").val('');
                    $("#authorText").val('');
                    $("#FormSubmit").show();
                } else {
                    var html = '<tr id="item_' + response["Id"] + '"><td>' +
                            '<div class="del_wrapper" align="center">\n\
                        <a href="#" class="del_button" id="del-' + response["Id"] + '">' +
                            '<img src="icon_del.gif" border="0" /></td><td>' +
                            '</a></div>' +
                            '<div class="del_wrapper" align="center">\n\
                        <a href="#" class="edit_button" id="del-' + response["Id"] + '">' +
                            '<img src="editicon.png" border="0" /></a></div>' +
                            '</td><td>' +
                            response["Id"] + '.</td><td> ' +
                            response["Name"] + '</td><td> ' +
                            response["Year"] + '</td><td> ' +
                            response["Author"] + '</td></tr>';
                    $("#responds").append(html);
                    $("#contentText").val('');
                    $("#yearText").val('');
                    $('#my-select').multiSelect();
                    $("#FormSubmit").show();
                }
            }
        });
    });

    // delete Books
    $("body").on("click", "#responds .del_button", function (e) {
        e.preventDefault();
        if (confirm('Czy na pewno chcesz usunąć pozycję?')) {

            var clickedID = this.id.split('-');
            var Db_ID = clickedID[1];
            var myData = "recordToDelete=" + Db_ID;

            $(this).hide();

            jQuery.ajax({
                type: "POST",
                url: "response.php",
                dataType: "JSON",
                data: myData,
                success: function () {
                    $('#item_' + Db_ID).fadeOut();
                }
            });

        }
    });
    // update Books
    $("body").on("click", "#responds .edit_button", function (e) {
        e.preventDefault();
        var clickedID2 = this.id.split('-');
        var Db_ID2 = clickedID2[1];

        var x = $('tr#item_' + Db_ID2 + ' td:nth-child(4)').html();
        console.log(x);
        var titleEdit = prompt("Edycja - Tytuł Ksiązki", x);
        if (titleEdit === null) {
            return;
        } else {
            while (titleEdit === '') {
                alert("Wpisz wartosc!");
                var titleEdit = prompt("Edycja - Tytuł Ksiązki");
            }
        }
        var y = $('tr#item_' + Db_ID2 + ' td:nth-child(5)').html();
        var yearEdit = prompt("Edycja - Rok wydania Ksiązki", y);
        if (yearEdit === null) {
            return;
        } else {
            while (!$.isNumeric(yearEdit) || yearEdit.length !== 4
                    || yearEdit < 1 || Math.round(yearEdit) === yearEdit) {
                alert("Wpisz wartosc!");
                var yearEdit = prompt("Edycja - Rok wydania Ksiązki");
            }
        }
        var z = $('tr#item_' + Db_ID2 + ' td:nth-child(6)').html();
        var authorsEdit = prompt("Edycja - Autorzy(Wypisz po przecinku)", z);
        if (authorsEdit === null) {
            return;
        } else {
            while (authorsEdit === '') {
                alert("Wpisz wartosc!");
                var authorsEdit = prompt("Edycja - Autorzy(Wpisz po przecinku)");
            }
        }




        var arr2 = {
            content_txt2: titleEdit,
            year_txt2: yearEdit,
            id_txt2: Db_ID2,
            author_txt2: authorsEdit
        };
        jQuery.ajax({
            type: "POST",
            url: "response.php",
            dataType: "JSON",
            data: arr2,
            success: function (response) {
                console.log(response);
                var html2 = '<td>' +
                        '<div class="del_wrapper" align="center">\n\
                        <a href="#" class="del_button" id="del-' + response["Id2"] + '">' +
                        '<img src="icon_del.gif" border="0" /></td><td>' +
                        '</a></div>' +
                        '<div class="del_wrapper" align="center">\n\
                        <a href="#" class="edit_button" id="del-' + response["Id2"] + '">' +
                        '<img src="editicon.png" border="0" /></td><td>' +
                        '</a></div>' +
                        response["Id2"] + '.</td><td> ' +
                        response["Name2"] + '</td><td> ' +
                        response["Year2"] + '</td><td>' +
                        response["Author2"] + '</td></tr>';
                $("#responds #item_" + Db_ID2).html(html2);
                $("#contentText").val('');
                $("#yearText").val('');

            }
        });
    });

    // delete Author
    $("body").on("click", "#authors .del_button", function (e) {
        e.preventDefault();
        if (confirm('Czy na pewno chcesz usunąć pozycję?')) {
            var clickedID3 = this.id.split('-');
            var Db_ID3 = clickedID3[1];
            var myData = "authorToDelete=" + Db_ID3;



            jQuery.ajax({
                type: "POST",
                url: "response.php",
                dataType: "JSON",
                data: myData,
                success: function (response) {

                    if (response["Error2"] === 1) {
                        $('#item_' + Db_ID3).fadeOut();
                    } else {
                        alert('Nie mozliwe jest usuniecie tego autora');

                    }
                }
            });
        }
    });


    // update Author
    $("body").on("click", "#authors .edit_button", function (e) {
        e.preventDefault();
        var clickedID4 = this.id.split('-');
        var Db_ID4 = clickedID4[1];
        var q = $('tr#item_' + Db_ID4 + ' td:nth-child(4)').html();
        var nameEdit = prompt("Edycja - Nazwa Autora", q);
        if (nameEdit === null) {
            return;
        } else {
            while (nameEdit === '') {
                alert("Wpisz wartosc!");
                var nameEdit = prompt("Edycja - Nazwa Autora");
            }
        }
        var arr3 = {
            content_txt3: nameEdit,
            id_txt3: Db_ID4
        };
        jQuery.ajax({
            type: "POST",
            url: "response.php",
            dataType: "JSON",
            data: arr3,
            success: function (response) {
                console.log(response);
                var html4 = '<td>' +
                        '<div class="del_wrapper" align="center">\n\
                        <a href="#" class="del_button" id="del-' + response["Id3"] + '">' +
                        '<img src="icon_del.gif" border="0" /></td><td>' +
                        '</a></div>' +
                        '<div class="del_wrapper" align="center">\n\
                        <a href="#" class="edit_button" id="del-' + response["Id3"] + '">' +
                        '<img src="editicon.png" border="0" /></a></div>' +
                        '</td><td>' +
                        response["Id3"] + '.</td><td>' +
                        response["Name3"] + '</td>';

                $("#authors #item_" + Db_ID4).html(html4);


            }
        });
    });



    $("#FormSubmit2").click(function (e) {
        e.preventDefault();
        if ($("#authortext2").val() === '') {
            alert("Wpisz wartosc!");
            return false;
        }
        $("#FormSubmit2").hide();
        var arr4 = {
            author_txt2: $("#authorText2").val()
        };

        jQuery.ajax({
            type: "POST",
            url: "response.php",
            dataType: "JSON",
            data: arr4,
            success: function (response) {
                console.log(response);

                var html5 = '<tr id="item_' + response["Id5"] + '"><td>' +
                        '<div class="del_wrapper" align="center">\n\
                        <a href="#" class="del_button" id="del-' + response["Id5"] + '">' +
                        '<img src="icon_del.gif" border="0" /></td><td>' +
                        '</a></div>' +
                        '<div class="del_wrapper" align="center">\n\
                        <a href="#" class="edit_button" id="del-' + response["Id5"] + '">' +
                        '<img src="editicon.png" border="0" /></a></div>' +
                        '</td><td>' +
                        response["Id5"] + '.</td><td>' +
                        response["Author5"] + '</td></tr>';

                $("#authors").append(html5);
                $("#authorText2").val('');
                $("#FormSubmit2").show();
            }

        });

    });

});