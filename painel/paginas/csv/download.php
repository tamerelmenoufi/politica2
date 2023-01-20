<button xls type="button" class="btn btn-primary btn-sm">
    <i class="fa-solid fa-download"></i>
</button>

<script>
    $(function(){
        $("button[xls]").click(function(){
            busca = $('input[type="search"]').val();
            window.open('paginas/csv/csv.php?busca='+busca);
        });
    })
</script>