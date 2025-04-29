# Funcionamento do Sistema de Locadoura de Veículos com PHP e BooTstrap

Este documento descreve o funcionamento do sistema de locadora de veículos desenvolvido em PHP utilizando Bootstrap para a interface, com autenticação de usuários, gerenciamento de veículos (carros e motos) e persistência de dados em arquivos JSON. O foco principal é explicar o funcionamento geral do sistema, com ênfase especial nos perfis de acesso (admin e usuário).

## 1. Visão Geral do Sistema

O Sistema de locadora de veículos e uma aplicação web que permite:
- Autneticação de usuário xom dois perfis: **admin** (administrador) e **usuário**
- Gerenciamento de veículos: cadastro, alguel, devolução e exclusão;
- Cálculo de previsão de aluguel: com base no tipo de veículo (carro ou moto) e número de dias;
- Interface responsiva.

Os dados armazenados em dois arquivos JSON:
- `usuário.json`: username, senha criptograda e perfil
- `Veiculos.json`: tipo, modelo, placa e status de disponibilidade 

## 2. Estrutura do sistema
O Sistema Utiliza:
- **PHP**: para a lógica
- **Bootstrap**: para a estilização
- **Bootstrap Icons**: para os ícones da interface
- **Composer**: para autoloading de classes
- **JSON**: para persistência de dados

### 2.1 Componentes principais 
- **Interfaces**: Define a Interface `Locavel` para veiculos e utiliza os métodos `alugar()`, e `inDisponivel()`
- **Moddels**: classes `Veiculos` (abstrata), `Carro`e `Moto` para os veículos, com cálculo de aluguel baseado em diárias constantes
(`DIARIA_CARRO` e `DIARIA_MOTO`)
- **Services**: Classes `AUTH` (autenticação e gerenciamento de usuários) e `Locadora` (gerenciamento dos veículos)
- **Views**: Template principal `template.php` para renderizar a interface e `login.php` para a autenticação
- **Controllers**: Lógica em `index.php` para processar requisições e carregar o template.  