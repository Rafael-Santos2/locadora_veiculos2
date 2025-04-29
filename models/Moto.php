<?php
namespace Models;
use Interfaces\Locavel;

// Classe que representa as motos
class Moto extends Veiculo implements Locavel
{
    public function calcularAluguel(int $dias): float {
        return $dias * DIARIA_MOTO;
    }

    public function alugar(): string
    {
        if ($this->disponivel){
            $this->disponivel = false;
            return "Moto '{$this->modelo}' alugada com sucesso!";
        }
        return "Moto '{$this->modelo}' não está disponivel.";
    }

    public function devolver(): string
    {
        if (!$this->disponivel){
            $this->disponivel = true;
            return "Moto '{$this->modelo}' devolvido com sucesso!";
        }
        return "Moto '{$this->modelo}' já está disponivel.";
    }
}