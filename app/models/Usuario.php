<?php
class Usuario implements JsonSerializable {
    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __construct($id = null, $nome = "", $email = "", $senha = "") {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
    }

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }

    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = $email; }
    public function setSenha($senha) { $this->senha = password_hash($senha, PASSWORD_DEFAULT); }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email
        ];
    }
}
?>
