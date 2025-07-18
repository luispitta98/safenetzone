<?php
class ServicoBloqueado implements JsonSerializable {
    private $id;
    private $whatsappBloqueado;
    private $discordBloqueado;
    private $tiktokBloqueado;

    public function __construct($id = 1, $whatsapp = false, $discord = false, $tiktok = false) {
        $this->id = $id;
        $this->whatsappBloqueado = (bool)$whatsapp;
        $this->discordBloqueado = (bool)$discord;
        $this->tiktokBloqueado = (bool)$tiktok;
    }

    public function getId() { return $this->id; }
    public function getWhatsappBloqueado() { return $this->whatsappBloqueado; }
    public function getDiscordBloqueado() { return $this->discordBloqueado; }
    public function getTiktokBloqueado() { return $this->tiktokBloqueado; }

    public function setWhatsappBloqueado($v) { $this->whatsappBloqueado = (bool)$v; }
    public function setDiscordBloqueado($v) { $this->discordBloqueado = (bool)$v; }
    public function setTiktokBloqueado($v) { $this->tiktokBloqueado = (bool)$v; }

    public function jsonSerialize() {
        return [
            'whatsappBloqueado' => $this->whatsappBloqueado,
            'discordBloqueado' => $this->discordBloqueado,
            'tiktokBloqueado' => $this->tiktokBloqueado
        ];
    }
}
?>
