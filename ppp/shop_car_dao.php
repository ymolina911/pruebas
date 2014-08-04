<?php
include_once('../el/shop_car.php');
include_once('../files/config/conexion.php');
include_once('../files/config/tabla.php');
//
class shop_car_dao
{
	//
	public $obj_shop_car;

	private $obj_tabla;
	private $obj_conexion;
	//
	const TABLA = 'shop_car';
	//
	public function __construct()
	{
		//
		$this->obj_shop_car = new shop_car;
		
		$this->obj_tabla= new tabla;
		$this->obj_conexion= new conexion;
	}
	
	//
	private function procesar_shop_car($proceso,$str_fields,$str_fields_op,$str_fields_values,$str_where_fields,$str_where_op,$str_where_values)
	{
		//
		$this->obj_tabla->set_tabla(self::TABLA);
		//
		$fields= explode(";",$str_fields);
		$fields_op= explode(";",$str_fields_op);
		$fields_values= explode(";",$str_fields_values);
		//
		$this->obj_tabla->set_fields($fields);
		$this->obj_tabla->set_operators_fields($fields_op);
		$this->obj_tabla->set_values_fields($fields_values);
		//
		$where_fields= explode(";",$str_where_fields);
		$where_op= explode(";",$str_where_op);
		$where_values= explode(";",$str_where_values);
		//
		$this->obj_tabla->set_fields_where($where_fields);
		$this->obj_tabla->set_operators_where($where_op);
		$this->obj_tabla->set_values_where($where_values);
		//
		$proceso=$this->obj_conexion->procesar_registros($this->obj_tabla,$proceso);
		return $proceso;
	}
	//
	public function ejecutar_insertar_shop_car($obj_shop_car_dao,$idacceso)
	{
		//
		$idarticulo = $obj_shop_car_dao->get_idarticulo();
		$idcatalogo = $obj_shop_car_dao->get_idcatalogoprod();
		
		$query= 'SELECT art.descripcion, dctg.precio FROM articulo art
				 INNER JOIN det_catalogoprod dctg ON art.idarticulo = dctg.idarticulo
				 WHERE art.idarticulo = '.$idarticulo.' AND art.estado = 0 AND dctg.idcatalogoprod = '.$idcatalogo;
		$result= $this->obj_conexion->query($query);

		$row=mysqli_fetch_array($result);

		$idcar= $this->obj_conexion->generar_idnum(self::TABLA,"idcar");
		$cantidad = 1;
		$subtotal = $cantidad * $row[1];

		$str_fields_values= $idacceso.";".$idcar.";".$idcatalogo.";".$idarticulo.";".$row[0].";".$row[1].";".$cantidad.";".$subtotal.";ped";
		//echo $str_fields_values;
		$this->procesar_shop_car("insert","","",$str_fields_values,"","","");
		
		$tipo = "ped";
		$listar= $this->ejecutar_listar_shop_car($idacceso,$tipo);
		
		return $listar;
	}
        
        public function ejecutar_actualizar_shop_car($obj_shop_car_dao,$idacceso)
        {
            $idarticulo = $obj_shop_car_dao->get_idarticulo();
            $idcatalogo = $obj_shop_car_dao->get_idcatalogoprod();
            $cantidad = $obj_shop_car_dao->get_cantidad();
            
            $query='SELECT art.descripcion, dctg.precio FROM articulo art
                    INNER JOIN det_catalogoprod dctg ON art.idarticulo = dctg.idarticulo
                    WHERE art.idarticulo = '.$idarticulo.' AND art.estado = 0 AND dctg.idcatalogoprod = '.$idcatalogo;
            $result=$this->obj_conexion->query($query);
            $row=mysqli_fetch_array($result);
            $nombre=$row[0];
            $precio=$row[1];
            
            $query='select idcar, cantidad from shop_car where idacceso="'.$idacceso.'" and idcatalogoprod="'.$idcatalogo.'" and idarticulo="'.$idarticulo.'"';
            $result=$this->obj_conexion->query($query);
            $row=mysqli_fetch_array($result);
            $num=mysqli_num_rows($result);
            if($num>0)
            {
                $idcar=$row[0];
                $cantAnt=$row[1];
                
                $part=explode('_', $cantidad);
                if($part[1]!='')
                {
                    $cantNue=$part[0];
                }
                else
                {
                    $cantNue=$cantAnt+$cantidad;
                }          
                
                $subtotal=$cantNue*$precio;
                
                $str_fields= 'cantidad;subtotal';
                $str_fields_op= '=;=';
                $str_fields_values= $cantNue.';'.$subtotal;
                
                $str_where_fields= "idacceso;idcar;idcatalogoprod;idarticulo;";
		$str_where_op= "=;=;=;=";
		$str_where_values= $idacceso.';'.$idcar.';'.$idcatalogo.';'.$idarticulo;
                
                $actualizar=$this->procesar_shop_car('update',$str_fields,$str_fields_op,$str_fields_values,$str_where_fields,$str_where_op,$str_where_values);
                
                $tipo = "ped";
                $listar=  $this->ejecutar_listar_shop_car($idacceso, $tipo);
            }
            else
            {
                $idcar=$this->obj_conexion->generar_idnum(self::TABLA,"idcar");
                $subtotal=$cantidad*$precio;
                $str_fields_values= $idacceso.";".$idcar.";".$idcatalogo.";".$idarticulo.";".$nombre.";".$precio.";".$cantidad.";".$subtotal.";ped";
                $insertar=$this->procesar_shop_car("insert","","",$str_fields_values,"","","");
                $tipo = "ped";
		$listar= $this->ejecutar_listar_shop_car($idacceso,$tipo);
            }
            return $listar;
        }
        
        public function ejecutar_restar_shop_car($obj_shop_car_dao,$idacceso)
	{
		//
		$idarticulo = $obj_shop_car_dao->get_idarticulo();
		$idcatalogo = $obj_shop_car_dao->get_idcatalogoprod();
		
		$query= 'SELECT art.descripcion, dctg.precio FROM articulo art
				 INNER JOIN det_catalogoprod dctg ON art.idarticulo = dctg.idarticulo
				 WHERE art.idarticulo = '.$idarticulo.' AND art.estado = 0 AND dctg.idcatalogoprod = '.$idcatalogo;
		$result= $this->obj_conexion->query($query);

		$row=mysqli_fetch_array($result);

		$idcar= $this->obj_conexion->generar_idnum(self::TABLA,"idcar");
		$cantidad = -1;
		$subtotal = $cantidad * $row[1];

		$str_fields_values= $idacceso.";".$idcar.";".$idcatalogo.";".$idarticulo.";".$row[0].";".$row[1].";".$cantidad.";".$subtotal.";ped";
		//echo $str_fields_values;
		$this->procesar_shop_car("insert","","",$str_fields_values,"","","");
		
		$tipo = "ped";
		$listar= $this->ejecutar_listar_shop_car($idacceso,$tipo);
		
		return $listar;
	}
        
	//////////////////////////
	public function ejecutar_insertar_ord_shop_car($obj_shop_car_dao,$idacceso)
	{
		//
		$idarticulo = $obj_shop_car_dao->get_idarticulo();
		$titulo = $obj_shop_car_dao->get_titulo();
		$precio = $obj_shop_car_dao->get_precio();

		$idcar= $this->obj_conexion->generar_idnum(self::TABLA,"idcar");
		$cantidad = 1;
		$subtotal = $cantidad * $precio;


		$str_fields_values= $idacceso.";".$idcar.";0;".$idarticulo.";".$titulo.";".$precio.";".$cantidad.";".$subtotal.";ord";
		//echo $str_fields_values;
		$this->procesar_shop_car("insert","","",$str_fields_values,"","","");
		
		$tipo = "ord";
		$listar= $this->ejecutar_listar_shop_car($idacceso,$tipo);
		
		return $listar;
	}
	
	public function ejecutar_eliminar_shop_car($obj_shop_car_dao,$idacceso,$tipo){
		
		$idarticulo = $obj_shop_car_dao->get_idarticulo();
		$idcatalogo = $obj_shop_car_dao->get_idcatalogoprod();
		//
		$str_where_fields= "idarticulo;idcatalogoprod;idacceso";
		$str_where_op="=;=;=";
		$str_where_values= $idarticulo.";".$idcatalogo.";".$idacceso;
		//echo $str_where_values;
		//exit();
		$this->procesar_shop_car("delete",$str_fields,$str_fields_op,$str_fields_values,$str_where_fields,$str_where_op,$str_where_values);
		$listar= $this->ejecutar_listar_shop_car($idacceso,$tipo);
		
		return $listar;
		
	}
	//
	public function ejecutar_eliminar_all_shop_car($idacceso,$tipo){
			
		$str_where_fields= "idacceso;estado";
		$str_where_op= "=;=";
		$str_where_values= $idacceso.";".$tipo;
		//
		$this->procesar_shop_car("delete",$str_fields,$str_fields_op,$str_fields_values,$str_where_fields,$str_where_op,$str_where_values);
		//$tipo = "ped";
		$listar= $this->ejecutar_listar_shop_car($idacceso,$tipo);
		
		return $listar;
		
	}
	//
	public function ejecutar_listar_shop_car($idacceso,$tipo){
		//armando la lista de pedi
		if($tipo == 'ped'){
			$query= 'SELECT spc.idarticulo, spc.titulo, spc.precio, SUM( spc.cantidad ) , SUM( spc.subtotal ), ctg.codcatalogo
				 FROM shop_car spc
				 INNER JOIN catalogoprod ctg ON spc.idcatalogoprod = ctg.idcatalogoprod
				 WHERE spc.idacceso = '.$idacceso.' AND spc.estado = "ped"
				 GROUP BY spc.idarticulo, spc.idcatalogoprod';
		}else{
			$query= 'SELECT spc.idarticulo, spc.titulo, spc.precio, SUM( spc.cantidad ) , SUM( spc.subtotal )
					FROM shop_car spc
					WHERE spc.idacceso = '.$idacceso.' AND spc.estado = "ord"
					GROUP BY spc.idarticulo';
		}	 
		//echo $query;
		$result= $this->obj_conexion->query($query);
		$numreg= mysqli_num_rows($this->obj_conexion->query($query));
		
		if($tipo == 'ped'){
			$select= '';
			$select .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
			$select .= '<tr>';
			$select .= '<td colspan="6"><div class="titulo">Lista de pedidos</div></td>';
			$select .= '</tr>';
			$select .= '<tr bgcolor="E5E5E5">
						<td style="padding: 5px;" colspan="2" align="center">Descripción</td>
						<td style="padding: 5px;" align="center">Precio Und.</td>
						<td style="padding: 5px;" align="center">Cantidad</td>
						<td style="padding: 5px;" align="center">Subtotal</td>
						<td style="padding: 5px;" align="center">Opciones</td>
						</tr>';
		}else{
			$select= '';
			$select .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';		
			$select .= '<tr>
						<td colspan="2" class="grid_titulo">ARTICULO</td>
						<td class="grid_titulo">PRECIO UND.</td>
						<td class="grid_titulo">CANTIDAD</td>
						<td class="grid_titulo">SUBTOTAL</td>
						<td class="grid_titulo">AGREGAR (+)</td>
						<td class="grid_titulo">QUITAR (-)</td>
						</tr>';
		}
		
		$total = 0;
		$count = 1;
	
		if($numreg>0){
			while($row=mysqli_fetch_array($result)){
				if($tipo == 'ped'){
					//obtener el id del catalogo
					$idcatalogo = explode("T",$row[5]);
					
					$dir_imagen='../files/images/articulos/'.$row[0].'.jpg';
					if(!file_exists($dir_imagen)){$dir_imagen='../files/images/articulos/notfound.jpg';}
					
					$select .= '<tr>';
					$select .= '<td style="padding: 5px;" width="80"><img src="'.$dir_imagen.'" width="70" height="70" />
					<input type="hidden" value="'.$idcatalogo[1].'" id="txtidcatalogo_'.$count.'">
					<input type="hidden" value="'.$row[0].'" id="txtidarticulo_'.$count.'">
			
					</td>';
					$select .= '<td style="padding: 5px; font-size: 16px;"><b>'.$row[1].'</b>
								<input type="hidden" value="'.$row[1].'" id="txtname_'.$count.'">	
								<div style="font-size: 12px;">Codigo catalogo: '.$row[5].'</div></td>';
					$select .= '<td align="center" style="padding: 5px;" width="100">S/.'.$row[2].'
								<input type="hidden" value="'.$row[2].'" id="txtprecio_und_'.$count.'"></td>';
					$select .= '<td align="center" style="padding: 5px;" width="60"><input type="text" id="cantidad_ped_'.$row[0].'" name="cantidad_ped" onkeypress="return validar_text_number(event)" value="'.$row[3].'" onFocus="this.select();" onKeyUp="callbackshop(\''.$row[0].';'.$idcatalogo[1].';unidades_'.$row[0].'\');"> Und.
								<input type="hidden" value="'.$row[3].'" id="txtcantidad_'.$count.'"></td>';
					$select .= '<td align="center" style="padding: 5px; color: #FF9316;" width="100"><b>S/.'.$row[4].'</b></td>';
					$select .= '<td align="center" style="padding: 5px;" width="90">';
                                        
                                        if($row[3]==1){$style=' style="display:none;"';}
                                        else{$style='';}
                                        
                                        $select .= '<a href="javascript:add_shopcar('.$row[0].','.$idcatalogo[1].',\'unidad\');cargar_shop_car();"><img src="../files/images/icons/button_plus.png" /></a>'
                                                . '<a href="javascript:add_shopcar('.$row[0].','.$idcatalogo[1].',\'resta\');cargar_shop_car();"'.$style.'><img src="../files/images/icons/button_minus.png" /></a>'
                                                . '<a href="javascript:delete_item_shop_car('.$row[0].','.$idcatalogo[1].');"><img src="../files/images/icons/button_cancel.png" /></a></td>';
					$select .= '</tr>';
					$select .= '<tr><td colspan="6" style="border-bottom: 1px solid #E5E5E5;"></td></tr>';
				
					$total += $row[4];
					$count++;
				}else{			
					$select .= '<tr>';
					$select .= '<td style="padding: 5px;">
					<input type="hidden" value="'.$row[0].'" id="txtidarticulo_'.$count.'">
					</td>';
					$select .= '<td style="padding: 5px; font-size: 14px;"><b>'.$row[1].'</b>
								<input type="hidden" value="'.$row[1].'" id="txtname_'.$count.'">	
								</td>';
					$select .= '<td align="center" style="padding: 5px;" width="80">S/.'.$row[2].'
								<input type="hidden" value="'.$row[2].'" id="txtprecio_und_'.$count.'"></td>';
					$select .= '<td align="center" style="padding: 5px;" width="60">'.$row[3].' Und.
								<input type="hidden" value="'.$row[3].'" id="txtcantidad_'.$count.'"></td>';
					$select .= '<td align="center" style="padding: 5px; color: #FF9316;" width="100"><b>S/.'.$row[4].'</b></td>';
					$select .= '<td align="center" style="padding: 5px;" width="30"><a class="averde" href="javascript:abastecer_articulo(\''.$row[0].'\')">add</a></td>';
					$select .= '<td align="center" style="padding: 5px;" width="30"><a class="arojo" href="javascript:delete_item_shop_car('.$row[0].',\'0\');">remove</a></td>';
					$select .= '</tr>';
					$select .= '<tr><td colspan="6" style="border-bottom: 1px solid #E5E5E5;"></td></tr>';
				
					$total += $row[4];
					$count++;
				}
			}
		}else{
			$select .= '<tr>';
			if($tipo == 'ped'){
				$select .= '<td colspan="6" align="center" style="padding: 20px 0;">Lista de pedidos vacia.</td>';
			}else{
				$select .= '<td colspan="7" align="center" style="padding: 20px 0;">Orden de compras vacia.</td>';
			}
			$select .= '</tr>';
		}
		
		if($tipo == 'ped'){
			$select .= '<tr bgcolor="E5E5E5"><td align="right" colspan="6" style="padding: 5px 75px; font-size: 15px;">Total: <b>S/.'.$total.'</b></td></tr>';
		}else{
			$select .= '<tr bgcolor="E5E5E5"><td align="right" colspan="6" style="padding: 5px 75px; font-size: 15px;">Total: <b>S/.'.$total.'</b></td><td></td></tr>';
		}
		
		$select .= '</table>';
		$select .= '<div class="button">';
		
		if($tipo == 'ped'){
			$select .= '<a href="javascript:enviar_pedido(\''.base64_encode($numreg).'\');">Enviar pedido</a>';
		}else{
			$select .= '<a href="javascript:enviar_orden(\''.base64_encode($numreg).'\');">Enviar orden de compra</a>';
		}
		$select .= '</div>';
		
		return $select;
	}
}
?>