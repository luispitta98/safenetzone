<?php
class Log implements JsonSerializable {
    private $id;
    private $timestamp;
    private $tempo;
    private $ip;
    private $codigo;
    private $tamanho;
    private $metodo;
    private $url;
    private $usuario;
    private $hierarquia;
    private $conteudoTipo;
    private $mac;
    private $tipoBloqueio;

    public function __construct($id, $timestamp, $tempo, $ip, $codigo, $tamanho, $metodo, $url, $usuario, $hierarquia, $conteudoTipo, $mac, $tipoBloqueio) {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->tempo = $tempo;
        $this->ip = $ip;
        $this->codigo = $codigo;
        $this->tamanho = $tamanho;
        $this->metodo = $metodo;
        $this->url = $url;
        $this->usuario = $usuario;
        $this->hierarquia = $hierarquia;
        $this->conteudoTipo = $conteudoTipo;
        $this->mac = $mac;
        $this->tipoBloqueio = $tipoBloqueio;
    }

    public function getId() { return $this->id; }
    public function getTimestamp() { return $this->timestamp; }
    public function getTempo() { return $this->tempo; }
    public function getIp() { return $this->ip; }
    public function getCodigo() { return $this->codigo; }
    public function getTamanho() { return $this->tamanho; }
    public function getMetodo() { return $this->metodo; }
    public function getUrl() { return $this->url; }
    public function getUsuario() { return $this->usuario; }
    public function getHierarquia() { return $this->hierarquia; }
    public function getConteudoTipo() { return $this->conteudoTipo; }
    public function getMac() { return $this->mac; }
    public function getTipoBloqueio() { return $this->tipoBloqueio; }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'tempo' => $this->tempo,
            'ip' => $this->ip,
            'codigo' => $this->codigo,
            'tamanho' => $this->tamanho,
            'metodo' => $this->metodo,
            'url' => $this->url,
            'usuario' => $this->usuario,
            'hierarquia' => $this->hierarquia,
            'conteudoTipo' => $this->conteudoTipo,
            'mac' => $this->mac,
            'tipoBloqueio' => $this->tipoBloqueio
        ];
    }
}
?>
