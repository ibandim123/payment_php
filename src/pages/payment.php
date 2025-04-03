<?php
require_once '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $remetente_id =  $_POST['remetente'];
  $destinatario = $_POST['destinatario'];
  $valor = $_POST['valor'];
  $tipo_conta = $_POST['tipo_conta'];
  $saldo = $_POST['saldo'];
  $nome = $_POST['nome'];

  if ($tipo_conta == 'pessoa') {
    if ($saldo > 0 && $valor <= $saldo) {

      $auth_url = "https://util.devi.tools/api/v2/authorize";
      $ch = curl_init($auth_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $auth_response = curl_exec($ch);
      print("Consultando serviço de autorizador externo...");
      print_r("</pre>");

      curl_close($ch);
      $auth_data = json_decode($auth_response, true);

      // print_r("<pre>");
      // print_r($auth_data);
      // print_r("</pre>");

      // $auth_data = [
      //   'status' => 'success',
      // ];


      if ($auth_data && isset($auth_data['status']) && $auth_data['status'] === "success") {
        print("Transferência autorizada...");
        print_r("</pre>");

        $conn->begin_transaction();

        try {

          $update_remetente = "UPDATE usuarios SET creditos = creditos - ? WHERE id = ?";
          $stmt1 = $conn->prepare($update_remetente);
          $stmt1->bind_param("ds", $valor, $remetente_id);
          $stmt1->execute();


          $update_destinatario = "UPDATE usuarios SET creditos = creditos + ? WHERE email = ?";
          $stmt2 = $conn->prepare($update_destinatario);
          $stmt2->bind_param("ds", $valor, $destinatario);
          $stmt2->execute();

          $conn->commit();
          echo "Transferência concluída com sucesso!";

          $notify_url = "https://util.devi.tools/api/v1/notify";
          $notify_data = [
            "email" => $destinatario,
            "message" => "Você recebeu uma transferência de R$ " . number_format($valor, 2, ',', '.') . " de " . $nome
          ];

          $ch = curl_init($notify_url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notify_data));
          $notify_response = curl_exec($ch);
          curl_close($ch);
          $notify_result = json_decode($notify_response, true);

          if ($notify_result && isset($notify_result['status']) && $notify_result['status'] === "success") {
            print_r("</pre>");
            echo " Notificação enviada com sucesso!";
            print_r("</pre>");
          } else {
            print_r("</pre>");
            echo " Erro ao enviar notificação," . $notify_result['message'];
            print_r("</pre>");
          }
        } catch (Exception $e) {
          $conn->rollback();
          print_r("</pre>");
          echo "Erro ao processar transferência: " . $e->getMessage();
          print_r("</pre>");
        }
      } else {
        print("Falha, Não houve autorização." . $auth_data['status']);
      }
    } else {
      print("Operação inválida, você deve ter saldo suficiente para realizar a transferência e ela deve ser menor com o total que você tem");
    }
  } else {
    print("Não é possível transferência para contas que não sejam de pessoa.");
  }
}
