<?php 
session_start();
include('protect.php');
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meta_calorias = $_POST["calorias"];
    $usuario_id = $_SESSION['idusuario'];

    $sql = "SELECT * FROM usuarios WHERE id = $usuario_id";
    $result = $conn->query($sql);

   
    if ($result->num_rows > 0) {
        // Já existe, então vamos atualizar a meta
        $update_sql = "UPDATE usuarios SET meta_calorias = $meta_calorias WHERE id = $usuario_id";
        if ($conn->query($update_sql) === TRUE) {
            echo "Meta de calorias atualizada com sucesso!";
        } else {
            echo "Erro ao atualizar a meta: " . $conn->error;
        }
    } else {
        // Não existe, então vamos inserir a meta
        $insert_sql = "INSERT INTO usuarios (id, meta_calorias) VALUES ($usuario_id, $meta_calorias)";
        if ($conn->query($insert_sql) === TRUE) {
            echo "Meta de calorias salva com sucesso!";
        } else {
            echo "Erro ao salvar a meta: " . $conn->error;
        }
    }
}


        
    


    echo "Valor da meta de calorias: $meta_calorias kcal.";

}
?>

<form method="post">
    Defina a sua meta de calorias diárias: <input type="number" name="calorias">
    <input type="submit" value="Salvar">
</form>