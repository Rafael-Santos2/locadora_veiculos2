<?php
// Define espaço pra organização do código
namespace Services;

class Auth{
    // Atributos
    private array $usuarios = [];

    // Método construtor para adicionar um usuário
    public function __construct(){
        $this ->carregarUsuarios();     
    }

    // Método para carregar os usuários do arquivo JSON
    private function carregarUsuarios(): void{
        // Verifica se o arquivo existe
        if (file_exists('ARQUIVO_USUARIOS')){
            // Lê o conteúdo e decodifica o JSON para o array
            $conteudo = json_decode(file_get_contents('ARQUIVO_USUARIOS'), true);
            // Verifica se o conteúdo não é nulo e é um array
            $this->usuarios = is_array($conteudo) ? $conteudo : [];
        } else {
            // Se o arquivo é um array
            $this->usuarios = [
                [
                    'username' => 'admin',
                    'password' => password_hash('admin123',         PASSWORD_DEFAULT),
                    'perfil' => 'admin'
                ],
                [
                    'username' => 'usuario',
                    'password' => password_hash('usuario123', PASSWORD_DEFAULT),
                    'perfil' => 'usuario'
                ]
            ];
            $this ->salvarUsuarios(); // Salva os usuários no arquivo
        }
    }
    // Função para salvar os usuários no arquivo JSON
    private function salvarUsuarios(): void{
        $dir = dirname(ARQUIVO_USUARIOS);

        if (!is_dir($dir)){
            mkdir($dir, 0777, true); // Cria o diretório se não existir
        }
        // Salva os usuários no arquivo JSON
        file_put_contents(ARQUIVO_USUARIOS, json_encode($this->usuarios, JSON_PRETTY_PRINT));
    }

    // Método para login de usuario
    public function login(string $username, string $password): bool{
        // Verifica se o usuário existe no array
        foreach ($this->usuarios as $usuario) {
            if ($usuario['username'] === $username && password_verify($password, $usuario['password'])) {
                $_SESSION['auth'] = [
                    'logado' => true,
                    'username' => ['username'],
                    'perfil' => $usuario['perfil']
                ];
                return true; // Retorna verdadeiro se o login for bem-sucedido
            }
        }
        return false; // Retorna falso se o login falhar
    }

    public function logout(): void{
        // Remove a sessão de autenticação
        session_destroy();
    }
    
    // Método para verificar se o usuário está logado
    public static function verificarLogin(): bool{
        return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === true;
    }

    public static function isPerfil(string $perfil): bool{
        return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === $perfil;
    }

    public static function isAdmin(): bool{
        return self::isPerfil('admin');
    }
    public static function getUsuario(): ?array{
        return $_SESSION['auth'] ?? null;
    }
}