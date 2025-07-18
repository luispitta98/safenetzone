<?php
class Configuracao implements JsonSerializable {
    private $id;
    private $nomeRede;
    private $senhaRede;

    public function __construct($id = 1, $nomeRede = '', $senhaRede = '') {
        $this->id = $id;
        $this->nomeRede = $nomeRede;
        $this->senhaRede = $senhaRede;
    }

    public function getId() { return $this->id; }
    public function getNomeRede() { return $this->nomeRede; }
    public function getSenhaRede() { return $this->senhaRede; }

    public function setNomeRede($v) { $this->nomeRede = $v; }
    public function setSenhaRede($v) { $this->senhaRede = $v; }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'nomeRede' => $this->nomeRede,
            'senhaRede' => $this->senhaRede
        ];
    }
}
?>
