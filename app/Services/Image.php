<?php

namespace App\Services;

use App\Models\ImageModel;
use Core\Constants\Constants;
use Lib\FileSystemHelper;

// coloquei uns comentarios pra entender o que to fazendo em portugues
class Image
{
    /** @var array<string, mixed> $image */
    private array $image;
    private string $file_name;

    /** @param array<string, mixed> $validations */
    public function __construct(
        private ImageModel $model,
        private string $storeDir,
        private array $validations = [],
    ) {}

    public function path(): string
    {
        if (!empty($this->model->image_name)) {
            // Generate MD5 hash of the avatar file to use as cache buster in URL
            // aqui gera o hash para forçar o navegador a recarregar se o arquivo mudar (por isso tbm da ?)
            $filePath = $this->getAbsoluteSavedFilePath();

            // Return the avatar URL with hash parameter to force browser to reload when file changes
            if (file_exists($filePath)) {
                $hash = md5_file($filePath);
                return $this->baseDir() . $this->model->image_name . '?' . $hash;
            }
        }

        return "/assets/images/defaults/no-image.png";
    }

    /**
     * Atualiza a imagem do modelo.
     * @param array<string, mixed> $image
     */
    public function upload(array $image): bool
    {
        $this->image = $image;

        if (!$this->isValidImage()) {
            return false;
        }
        $this->file_name = time() . "-" . md5_file($this->getTmpFilePath());
        // $this->file_name = md5_file($this->getTmpFilePath());

        $this->model->image_name = $this->getFileName();
        $this->model->image_type = $this->image['type'] ?? null;
        $this->model->image_size = $this->image['size'] ?? null;

        if ($this->model->save()) {
            $this->updateFile();
            return true;
        }
        // if ($this->updateFile()) {
        //     $this->model->update([
        //         'image_name' => $this->getFileName(),
        //     ]);

        //     return true;
        // }

        return false;
    }

    //Move o arquivo temporário para o diretório final.
    protected function updateFile(): bool
    {
        $tmpPath = $this->getTmpFilePath();
        if (empty($tmpPath)) {
            return false;
        }

        // $this->removeOldImage();

        $destination = $this->getAbsoluteDestinationPath();
        $resp = FileSystemHelper::move($tmpPath, $destination);

        if (!$resp) {
            $error = error_get_last();
            throw new \RuntimeException(
                'Falha ao mover o arquivo enviado: ' . ($error['message'] ?? 'Erro desconhecido')
            );
        }

        return true;
    }

    //Retorna o caminho temporário do upload.

    private function getTmpFilePath(): string
    {
        return $this->image['tmp_name'] ?? '';
    }

    //Remove a imagem antiga, qd existir.
    //private function removeOldImage(): void
    //{
    //    if ($this->model->image_name) {
    //        $oldPath = $this->getAbsoluteSavedFilePath();
    //        if (file_exists($oldPath)) {
    //            unlink($oldPath);
    //        }
    //    }
    //}

    //Gera o nome final do arquivo.
    private function getFileName(): string
    {
        $parts = explode('.', $this->image['name']);
        $extension = strtolower(end($parts));
        return "{$this->file_name}.{$extension}";
    }

    //Caminho absoluto onde o arquivo será salvo.
    private function getAbsoluteDestinationPath(): string
    {
        return $this->storeDir() . $this->getFileName();
    }

    //Caminho público usado em URLs.
    private function baseDir(): string
    {
        return "/assets/uploads/{$this->storeDir}/";
    }

    //Caminho absoluto para armazenamento no servidor.
    private function storeDir(): string
    {
        $path = Constants::rootPath()->join('public' . $this->baseDir());
        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }

    //Caminho absoluto do arquivo salvo.
    private function getAbsoluteSavedFilePath(): string
    {
        return Constants::rootPath()
            ->join('public' . $this->baseDir())
            ->join($this->model->image_name);
    }

    //Executa as validações configuradas
    private function isValidImage(): bool
    {
        if (isset($this->validations['extension'])) {
            $this->validateImageExtension();
        }

        if (empty($this->getTmpFilePath())) {
            $this->model->addError('image', 'Não pode ser vazia');
        }

        if (isset($this->validations['size'])) {
            $this->validateImageSize();
        }

        return $this->model->errors('image') === null;
    }

    //Valida a extensão do arquivo.
    private function validateImageExtension(): void
    {
        $parts = explode('.', $this->image['name']);
        $extension = strtolower(end($parts));

        if (!in_array($extension, $this->validations['extension'])) {
            $this->model->addError('image', 'Extensão de arquivo inválida.');
        }
    }

    //Valida o tamanho máximo permitido.
    private function validateImageSize(): void
    {
        if ($this->image['size'] > $this->validations['size']) {
            $this->model->addError('avatar', 'Tamanho do arquivo inválido');
        }
    }

    // CAMINHO PRA A PASTA 
    // monta o caminho onde do repositorio pra saber onde excluir as coisas 
    public function getStoreDir(): string
    {
        return $this->storeDir;
    }

    // DELETA IMAGEM

    public function deleteImage(): bool
    {
        if (empty($this->model) || empty($this->model->image_name)) {
            return false;
        }

        $path = $this->getAbsoluteSavedFilePath();

        // Salva o caminho do diretório antes de destruir a model
        // Isso garante que você tenha o ID do banheiro, mesmo que o registro seja removido.
        $storeDir = $this->storeDir;

        // 💡 PASSO 2: Remove o registro do banco de dados (destrói o objeto Model)
        if ($this->model->destroy()) {

            // Remove o arquivo físico
            if (file_exists($path)) {
                @unlink($path); // Usar @ para evitar erros se o arquivo não existir
            }

            $this->deleteStoreDirIfEmpty($storeDir);

            return true;
        }

        return false;
    }


    //FUNÇÂO PRA DELETAR PASTA VAZIA
    // Função que fiz pra conseguir excluir as pastas qd vazias pq não tava indo por reza brava ai fui no mais basico pq tava me perdendo nesse monte de configuração
    /**
     * Tenta remover o diretório de armazenamento se ele estiver vazio.
     * // pq esse B.O tava grande
     * @param string $storeDir O caminho do diretório lógico (ex: 'bathrooms/1/5').
     * @return bool
     */
    public function deleteStoreDirIfEmpty(string $storeDir): bool
    {
        // Calcula o caminho absoluto no servidor.
        //sim vai ter comentario bobo pra eu entender o q fiz no futuro (não remover)
        $dirPath = Constants::rootPath()->join('public/assets/uploads/' . $storeDir);

        // Verifica se o diretório existe
        if (!is_dir($dirPath)) {
            return false;
        }

        //Verifica se o diretório está vazio usando a função nativa `scandir`
        // scandir retorna a lista de arquivos/pastas. Se o array resultante 
        // tiver apenas 2 elementos (".", ".."), o diretório está vazio.
        // ou seja ve se não tem nada no diretorno atual e o outro o pai
        //
        $files = scandir($dirPath); // retorna tudo q tem no caminho no caso $dirPath q 
        // q setiver vazio vai voltar o (".", "..") que literalmente indica estar vazio

        // Verifica se a leitura foi bem-sucedida e se o único conteúdo é "." e ".."
        //ai o array_diff vai remover o (".", "..") ai vai bater se é === 0 sendo 0 apaga a pasta

        if ($files !== false && count(array_diff($files, ['.', '..'])) === 0) {

            //Remove o diretório usando a função nativa `rmdir`
            // Usamos @ para suprimir warnings caso o diretório não possa ser removido. (aqui foi suco da ia então to com duvida de como funciona 100%)
            return @rmdir($dirPath);
        }

        return false;
    }
}
