<?php

namespace App\Services;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;
use Lib\FileSystemHelper;

// coloquei uns comentarios pra entender o que to fazendo em portugues
class Image
{
    /** @var array<string, mixed> $image */
    private array $image;
    private string $file_name;

    /** @param array<string, mixed> $validations */
    public function __construct(
        private Model $model,
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
    private function removeOldImage(): void
    {
        if ($this->model->image_name) {
            $oldPath = $this->getAbsoluteSavedFilePath();
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }

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

    public function deleteImage(): bool
    {
      if (!isset($this->model) || !isset($this->model->image_name)) {
        return false;
      }
      $path = $this->getAbsoluteDestinationPath();
      if ($this->model->destroy()) {
        unlink($path);
        return true;
      }
      return false;
    }
}
