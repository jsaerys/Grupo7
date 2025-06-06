<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../modelo/mascota.php';
require_once '../modelo/cliente.php';
require_once '../modelo/venta.php';
require_once '../modelo/cita.php';
require_once '../modelo/producto.php';

$method = $_SERVER['REQUEST_METHOD'];
$entity = isset($_GET['entity']) ? $_GET['entity'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

$data = json_decode(file_get_contents("php://input"));

function handleRequest($model, $data, $action) {
    if ($action == 'read' && $_SERVER['REQUEST_METHOD'] == 'GET') {
        $stmt = $model->leer();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $items_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($items_arr, $row);
            }
            http_response_code(200);
            echo json_encode($items_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron registros."));
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($action == 'create') {
            foreach ($data as $key => $value) {
                $model->$key = htmlspecialchars(strip_tags($value));
            }
            if ($model->crear()) {
                http_response_code(201);
                echo json_encode(array("message" => "Registro creado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear el registro."));
            }
        } elseif ($action == 'update') {
            if (!isset($data->id)) {
                http_response_code(400);
                echo json_encode(array("message" => "ID no proporcionado para la actualización."));
                return;
            }
            foreach ($data as $key => $value) {
                $model->$key = htmlspecialchars(strip_tags($value));
            }
            if ($model->actualizar()) {
                http_response_code(200);
                echo json_encode(array("message" => "Registro actualizado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo actualizar el registro."));
            }
        } elseif ($action == 'delete') {
            if (!isset($data->id)) {
                http_response_code(400);
                echo json_encode(array("message" => "ID no proporcionado para la eliminación."));
                return;
            }
            $model->id = htmlspecialchars(strip_tags($data->id));
            if ($model->eliminar()) {
                http_response_code(200);
                echo json_encode(array("message" => "Registro eliminado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo eliminar el registro."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Acción no válida para POST."));
        }
    } else {
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido."));
    }
}

switch ($entity) {
    case 'mascotas':
        $mascota = new Mascota();
        handleRequest($mascota, $data, $action);
        break;

    case 'clientes':
        $cliente = new Cliente();
        handleRequest($cliente, $data, $action);
        break;

    case 'ventas':
        $venta = new Venta();
        handleRequest($venta, $data, $action);
        break;

    case 'citas':
        $cita = new Cita();
        handleRequest($cita, $data, $action);
        break;

    case 'productos':
        $producto = new Producto();
        handleRequest($producto, $data, $action);
        break;

    default:
        http_response_code(400);
        echo json_encode(array("message" => "Entidad no especificada o no válida."));
        break;
}
?>