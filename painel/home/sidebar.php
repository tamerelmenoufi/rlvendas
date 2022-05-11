<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion menus" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa-solid fa-burger"></i>
        </div>
        <div class="sidebar-brand-text mx-3" title="Sistema de Gestão Política">YOBOM</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dahboard -->
    <li class="nav-item active">
        <a class="nav-link" href="./">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span></a>
    </li>


    <li class="nav-item active">
        <a class="nav-link" href="#" AbrirVendas>
            <i class="fa-solid fa-house"></i>
            <span>Vendas</span></a>
    </li>


    <!-- Divider  -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <!-- <div class="sidebar-heading">Produtos</div> -->
    <!-- Nav Item - Configuração -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#MenuCardapio"
           aria-expanded="true" aria-controls="MenuCardapio">
            <i class="fa-solid fa-clipboard-list"></i>
            <span>Cardápio</span>
        </a>
        <div id="MenuCardapio" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Configurações:</h6> -->
                <?php
                $query = "SELECT * FROM categorias WHERE deletado != '1' ORDER BY categoria";
                $result = mysqli_query($con, $query);
                while ($c = mysqli_fetch_object($result)) {
                    ?>
                    <a class="collapse-item" href="#"
                       url="produtos/index.php?categoria=<?= $c->codigo ?>"><?= ucfirst(mb_strtolower($c->categoria, 'UTF-8')); ?></a>
                    <?php
                }
                ?>

            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#MenuVendas"
           aria-expanded="true" aria-controls="MenuVendas">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Vendas</span>
        </a>

        <div id="MenuVendas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#" url="vendas/index1.php?opc=producao">Novos</a>
                <a class="collapse-item" href="#" url="vendas/index1.php?opc=preparo">Preparo</a>
                <a class="collapse-item" href="#" url="vendas/index1.php?opc=pagar">Pagar</a>
                <a class="collapse-item" href="#" url="vendas/index1.php?opc=pago">Pago</a>
                <a class="collapse-item" href="#" url="vendas/index1.php?opc=cancelados">Cancelados</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#MenuConfiguracoes"
           aria-expanded="true" aria-controls="MenuConfiguracoes">
            <i class="fas fa-fw fa-cog"></i>
            <span>Configurações</span>
        </a>
        <div id="MenuConfiguracoes" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Configurações:</h6> -->
                <a class="collapse-item" href="#" url="categorias/index.php">Categorias</a>
                <a class="collapse-item" href="#" url="categorias_medidas/index.php">Medidas</a>
                <a class="collapse-item" href="#" url="mesas/index.php">Mesas</a>
                <a class="collapse-item" href="#" url="pagamentos/index.php">Pagamentos</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#MenuUsuarios"
           aria-expanded="true" aria-controls="MenuUsuarios">
            <i class="fa-solid fa-users"></i>
            <span>Usuários</span>
        </a>
        <div id="MenuUsuarios" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Configurações:</h6> -->
                <a class="collapse-item" href="#" url="atendentes/index.php">Atendentes</a>
                <a class="collapse-item" href="#" url="usuarios/index.php">Usuários</a>
                <a class="collapse-item" href="#" url="clientes/index.php">Clientes</a>
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" opc="0"></button>
    </div>
</ul>

<script>
    $(function () {
        $("#sidebarToggle").click(function () {
            opc = $(this).attr('opc');
            if (opc == '0') {
                $("#page-top").addClass('sidebar-toggled');
                $(".menus").addClass('toggled');
                $(this).attr("opc", "1");
            } else {
                $("#page-top").removeClass('sidebar-toggled');
                $(".menus").removeClass('toggled');
                $(this).attr("opc", "0");
            }

        });

        $(document).on('click', '[url]', function (e) {
            e.preventDefault();

            var url = $(this).attr('url');

            $('.loading').fadeIn(200);

            $.ajax({
                url,
                success: function (data) {
                    $('#palco').html(data);
                }
            })
            .done(function () {
                $('.loading').fadeOut(200);
            })
            .fail(function (error) {
                alert('Error');
                $('.loading').fadeOut(200);
            })
        });

        $("a[AbrirVendas]").click(function(){
            $('.loading').fadeIn(200);

            $.ajax({
                url:"vendas/home.php",
                success: function (data) {
                    $(".TelaVendas").html(data);
                }
            })
            .done(function () {
                $('.loading').fadeOut(200);
                $(".TelaVendas").css("display","block");
            })
            .fail(function (error) {
                alert('Error');
                $('.loading').fadeOut(200);
            })


        });

    })
</script>