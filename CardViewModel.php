<?php

namespace App\ViewModels\Cards;

use App\ViewModels\ViewModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Суперкласс структуры данных модели в представлении.
 *
 * Class CardViewModel
 * @package App\ViewModels
 */
abstract class CardViewModel extends ViewModel
{
    /** @var  Model  $model Модель данных */
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->data();

        $this->ignore = array_merge($this->ignore, ['data']);
    }

    /**
     * Вернуть первичный ключ модели.
     *
     * @return integer|string
     */
    public function id()
    {
        return $this->model->getKey();
    }

    /** Метод должен содержать реализацию формирования данных */
    abstract protected function data(): void;
}
