<?php require_once('_system/bloqueia_view.php'); ?>
<form action="<?=DOMINIO?>lojascript/alterar_anuncio_grv" class="form-horizontal" method="post">  						

  <fieldset>
    
    <div class="form-group">
      <label class="col-md-12" >Anuncio</label>
      <div class="col-md-12">
        <input type="text" class="form-control"  name="anuncio_titulo" value="<?=$titulo?>" disabled="" >
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-12" >Selecione o produto para vincular ao anuncio ou crie um novo produto</label>
      <div class="col-md-12">
        <select name="produto" class="form-control select2" style="width: 100%;" >
          <option value="novo" >Criar novo produto</option>
          <?php

            foreach ($lista as $key => $value) {
              
              if($produto == $value['codigo']){
                $select = " selected ";
              } else {
                $select = "";
              }
              
              echo "<option value='".$value['codigo']."' $select >".$value['titulo']."</option>";

            }

          ?>
        </select>
      </div>
    </div>

  </fieldset>
  
  <div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <input type="hidden" name="anuncio" value="<?=$anuncio?>">
  </div>

</form>

<script>
$(document).ready(function() {
  $(".select2").select2();
});
</script>