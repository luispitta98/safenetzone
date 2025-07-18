<?php
class SiteProibido implements JsonSerializable {
    private $id;
    private $dominio;

    public function __construct($id = null, $dominio = "") {
        $this->id = $id;
        $this->dominio = $dominio;
    }

    public function getId() { return $this->id; }
    public function getDominio() { return $this->dominio; }

    public function setDominio($dominio) { $this->dominio = $dominio; }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'dominio' => $this->dominio
        ];
    }
}
?>
