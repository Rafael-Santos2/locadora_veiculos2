<?php

namespace Services;

use Models\{Veiculo, Carro, Moto};

class Locadora
{
    private array $veiculos = [];

    public function __construct()
    {
        $this->carregarVeiculos();
    }

    private function carregarVeiculos(): void
    {
        if (file_exists(ARQUIVO_JSON)) {

            // deocdifica o arquivo JSON
            $dados = json_decode(file_get_contents(ARQUIVO_JSON), true);
            // percorre o array de veiculos e cria os objetos correspondentes
            foreach ($dados as $dado) {
                if ($dados['tipo'] == 'Carro') {
                    $veiculo = new Carro($dado['modelo'], $dado['placa']);
                } else {
                    $veiculo = new Moto($dado['modelo'], $dado['placa']);
                }
                $veiculo->setDisponivel($dado['disponivel']);
                $this->veiculos[] = $veiculo;
            }
        }
    }
    // Salvar veiculos
    private function salvarVeiculos(): void
    {
        $dados = [];

        foreach ($this->veiculos as $veiculo) {
            $dados[] = [
                'tipo' => ($veiculo instanceof Carro) ? 'Carro' : 'Moto',
                'modelo' => $veiculo->getModelo(),
                'placa' => $veiculo->getPlaca(),
                'disponivel' => $veiculo->isDisponivel()
            ];

            $dir = dirname(ARQUIVO_JSON);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents(ARQUIVO_JSON, json_encode($dados, JSON_PRETTY_PRINT));
        }
    }

    // Adicionar novo veiculo
    public function adicionarVeiculo(Veiculo $veiculo): void
    {
        $this->veiculos[] = $veiculo;
        $this->salvarVeiculos();
    }

    // Remover veiculo
    public function deletarVeiculo(string $modelo, string $placa): string
    {
        foreach ($this->veiculos as $key => $veiculo) {

            // Verifica se o veiculo existe
            // Se o modelo e a placa do veiculo forem iguais aos informados, remove o veiculo
            if ($veiculo->getModelo() === $modelo && $veiculo->getPlaca() === $placa) {
                // remove o veiculo do array
                // unset($veiculo);
                unset($this->veiculos[$key]);

                // reorganizar os indices do array
                $this->veiculos = array_values($this->veiculos);

                // salvar os veiculos no arquivo JSON
                $this->salvarVeiculos();
                return "Veículo '{$modelo}' removido com sucesso!";
            }
        }
        return "Veículo não encontrado!";
    }

    // Alugar veiculo por n dias
    public function alugarVeiculo(string $placa, int $dias = 1): string
    {
        // percorre o array de veiculos e verifica se o veiculo existe
        foreach ($this->veiculos as $veiculo) {
            // Se o modelo do veiculo for igual ao informado, verifica se o veiculo está disponível
            if ($veiculo->getPlaca() === $placa && $veiculo->isDisponivel()) {
                // calcula o valor do aluguel
                $valorAluguel = $veiculo->calcularAluguel($dias);
                // Marcar o veiculo como alugado
                $mensagem = $veiculo->alugar();

                $this->salvarVeiculos();
                return $mensagem . "Valor do aluguel: R$" . number_format($valorAluguel, 2, ',', '.');
            }
        }
        return "Veículo não encontrado ou não disponível para aluguel!";
    }

    // Devolver o veiculo
    public function devolverVeiculo(string $placa): string{
        // Percorrer a lista de veiculos
        foreach ($this->veiculos as $veiculo) {
            // Verifica se o veiculo existe
            if ($veiculo->getPlaca() === $placa && !$veiculo->isDisponivel()){
                // Disponibilizar o veiculo
                $mensagem = $veiculo->devolver();

                // Salvar os veiculos no arquivo JSON
                $this->salvarVeiculos();
                return $mensagem;
            }
        }
        return "Veículo não encontrado ou não disponível para devolução!";
    }

    // Retorna a lista de veiculos
    public function listarVeiculos(): array
    {
        return $this->veiculos;
    }

    // Calcular previsão do valor

}
