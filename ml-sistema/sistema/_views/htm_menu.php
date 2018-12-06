<?php require_once('_system/bloqueia_view.php'); ?>
<aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu">
        
        <li class="header">NAVEGAÇÃO</li>
        
        <?php
        foreach ($_base['menu_lateral'] as $key => $value) {
          
          $titulo = $value['titulo'];
          $icone = $value['icone'];
          $endereco = $value['endereco'];
          $ativo = $value['ativo'];
          if($ativo){ $menu_ativo = "active"; } else { $menu_ativo = ""; }
          
          echo "
          <li class='treeview $menu_ativo'>
            <a href='".DOMINIO."$endereco'>
              <i class='fa fa-$icone'></i> <span>$titulo</span>
            </a>
          </li>
          ";
          
        }
        ?>
        
      </ul>
    </section>
</aside>