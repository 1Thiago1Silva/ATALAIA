$(document).ready(function () {
    // Autocomplete para o campo "Tipo"
    $("#tipo").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "pegarTipos.php", // Backend para buscar os tipos
                method: "POST",
                dataType: "json",
                data: { term: request.term }, // Termo pesquisado
                success: function (data) {
                    response(data); // Retorna os dados para o autocomplete
                },
            });
        },
        minLength: 2, // Quantidade mínima de caracteres para buscar (mantido aqui, pois é relevante para o campo "Tipo")
        select: function (event, ui) {
            $("#tipo").val(ui.item.label); // Preenche o campo com o nome do tipo
            $("#tipo").data("id", ui.item.value); // Salva o id do tipo

            // Ativa o campo subtipo
            $("#subtipo").prop("disabled", false); // Habilita o campo subtipo
            $("#subtipo").autocomplete("search", ""); // Chama a pesquisa do autocomplete para carregar os subtipos automaticamente

            return false; // Evita comportamento padrão
        },
    });

    // Autocomplete para o campo "Subtipo"
    $("#subtipo").autocomplete({
        source: function (request, response) {
            const idTipo = $("#tipo").data("id"); // Pega o idTipo selecionado

            if (!idTipo) {
                alert("Por favor, selecione um Tipo primeiro.");
                return;
            }

            $.ajax({
                url: "pegarSubtipos.php", // Backend para buscar os subtipos
                method: "POST",
                dataType: "json",
                data: { idTipo: idTipo, term: request.term }, // Envia o idTipo e o termo para pesquisa
                success: function (data) {
                    // Verifica se existem subtipos
                    if (data.length > 0) {
                        response(data); // Retorna os dados dos subtipos
                    } else {
                        response([{ label: "Nenhum subtipo encontrado", value: "" }]);
                    }
                },
            });
        },
        minLength: 0, // Nenhuma quantidade mínima de caracteres é necessária para o campo "Subtipo"
        select: function (event, ui) {
            $("#subtipo").val(ui.item.label); // Preenche o campo com o nome do subtipo
            return false;
        },
    });
});
