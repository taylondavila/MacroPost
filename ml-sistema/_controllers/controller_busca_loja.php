<?php
class busca_loja extends controller {
	
	public function init(){
		
	}
	
	public function inicial(){		
		
		$busca = $this->post('busca');

		$this->irpara(DOMINIO.'index/inicial/busca/'.$busca);
	}
	
}