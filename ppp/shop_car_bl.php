<?php


//

session_start();
include_once('../dl/shop_car_dao.php');
//variable que almacena la operacion a realizar
$accion=$_GET['accion'];
//insertar o actualizar un registro
if($accion=='insertar')
{
    $idacceso= $_SESSION['usuario_sesion'];	
    $idarticulo = $_GET['idarticulo'];
    $idcatalogo = $_GET['idcatalogo'];
    //
    $obj_shop_car_dao= new shop_car_dao;
    $obj_shop_car_dao->obj_shop_car->set_idarticulo($idarticulo);
    $obj_shop_car_dao->obj_shop_car->set_idcatalogoprod($idcatalogo);

    $listar= $obj_shop_car_dao->ejecutar_insertar_shop_car($obj_shop_car_dao->obj_shop_car,$idacceso);

    echo $listar;
}
else if($accion=='restar')
{
    $idacceso= $_SESSION['usuario_sesion'];	
    $idarticulo = $_GET['idarticulo'];
    $idcatalogo = $_GET['idcatalogo'];
    //
    $obj_shop_car_dao= new shop_car_dao;
    $obj_shop_car_dao->obj_shop_car->set_idarticulo($idarticulo);
    $obj_shop_car_dao->obj_shop_car->set_idcatalogoprod($idcatalogo);

    $listar= $obj_shop_car_dao->ejecutar_restar_shop_car($obj_shop_car_dao->obj_shop_car,$idacceso);

    echo $listar;
}
else if($accion=='carrito')
{
    $idacceso=$_SESSION['usuario_sesion'];
    $idarticulo=$_GET['idarticulo'];
    $idcatalogo=$_GET['idcatalogo'];
    $cantidad=$_GET['cantidad_ped'];
    
    $obj_shop_car_dao=new shop_car_dao();
    $obj_shop_car_dao->obj_shop_car->set_idarticulo($idarticulo);
    $obj_shop_car_dao->obj_shop_car->set_idcatalogoprod($idcatalogo);
    $obj_shop_car_dao->obj_shop_car->set_cantidad($cantidad);
    
    $carrito=$obj_shop_car_dao->ejecutar_actualizar_shop_car($obj_shop_car_dao->obj_shop_car,$idacceso);
}
else if($accion == 'eliminar')
{
    $idacceso= $_SESSION['usuario_sesion'];	
    $idarticulo = $_GET['idarticulo'];
    $idcatalogo = $_GET['idcatalogo'];
    $tipo = $_GET['tipo'];
    //
    $obj_shop_car_dao= new shop_car_dao;
    $obj_shop_car_dao->obj_shop_car->set_idarticulo($idarticulo);
    $obj_shop_car_dao->obj_shop_car->set_idcatalogoprod($idcatalogo);

    $listar= $obj_shop_car_dao->ejecutar_eliminar_shop_car($obj_shop_car_dao->obj_shop_car,$idacceso,$tipo);

    echo $listar;
}
else if($accion == 'eliminar_todo')
{
    $idacceso= $_SESSION['usuario_sesion'];
    $tipo = $_GET['tipo'];
    $obj_shop_car_dao= new shop_car_dao;
    $listar = $obj_shop_car_dao->ejecutar_eliminar_all_shop_car($idacceso,$tipo);
    echo $listar;
	
}
else if($accion == 'listar_shop_car')
{
    $idacceso= $_SESSION['usuario_sesion'];	
    $tipo = "ped";
    $obj_shop_car_dao= new shop_car_dao;
    $listar = $obj_shop_car_dao->ejecutar_listar_shop_car($idacceso,$tipo);
    echo $listar;
}

?>