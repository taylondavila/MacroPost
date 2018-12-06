<?php require_once('_system/bloqueia_view.php'); ?>
<form action="<?=$_base['objeto']?>novo_anuncio_grv" class="form-horizontal" method="post">  						

  <fieldset>
    
    <div class="form-group">
      <label class="col-md-12" >Categoria do Mercado Livre</label>
      <div class="col-md-12">
        <select name="categoria" class="form-control select2" style="width: 100%;" >
          <?php

            foreach ($categorias as $key => $value) {
              
              echo "<option value='".$value['codigo']."' >".$value['titulo']."</option>";
              
            }
          
          ?>
        </select>
      </div>
    </div>
    
  </fieldset>
  
  <div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <input type="hidden" name="produto" value="<?=$produto?>">
  </div>

</form>

<script>
$(document).ready(function() {
  $(".select2").select2();
});
</script>