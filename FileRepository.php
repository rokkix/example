<?php

namespace App\Files\Repositories;

use App\Files\File;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

/**
 * Class FileRepository
 * @package App\Files\Repositories
 *
 * @method string path(string $path)
 */
abstract class FileRepository
{
    /**
     * @var string path to the folder of storage.
     */
    protected $disk = ' ';

    /**
     * @var \App\Files\File $file.
     */
    protected $file;

    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter $storage.
     */
    protected $storage;

    /**
     * @var array files that should be ignored.
     */
    protected $ignore = ['.gitignore'];

    /**
     * Create a new FileRepository instance.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->storage = app(Factory::class)->disk($this->disk);

        $this->file = $file;
    }

    /**
     * Get files from directory
     *
     * @param string|null $directory
     * @param bool $all
     * @return \Illuminate\Support\Collection
     */
    public function get(string $directory = null, bool $all = true)
    {
        return $this->file->newCollection(
            $this->storage->files($directory, $all)
        )->diff($this->ignore)->map(function ($file) {
            return new $this->file($file);
        });
    }

    public function putAs(UploadedFile $file, string $name)
    {
        $request = app(Request::class);

        $path = "{$request->subDomain}_college_{$request->collegeId}";

        $path = $this->storage->putFileAs($path, $file, $name);

        return new $this->file($path);
    }

    public function files($directory = null, bool $recursive = false, $descending = false): Collection
    {
        return collect($this->storage->files($directory, $recursive))->sortBy(function ($item) {
            return $item;
        }, SORT_REGULAR, $descending);
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this->storage, $method)) {
            return $this->storage->{$method}(...$arguments);
        }

        throw new \BadMethodCallException("method {$method} not exists");
    }
}
