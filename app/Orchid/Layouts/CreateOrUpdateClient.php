<?php

namespace App\Orchid\Layouts;

use App\Models\Service;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\DateTimer;

class CreateOrUpdateClient extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $isClientExists = is_null($this->query->getContent('client')) === false;
        return [
            Input::make('client.id')->type('hidden'),
            Input::make('client.phone')->required()->title('телефон')->mask('(999)999-9999')->disabled($isClientExists),
               Group::make([
                Input::make('client.name')->placeholder('Имя клиента')->required()->title('имя'),
                Input::make('client.last_name')->placeholder('Фамилия клиента')->title('фамилия')->required(),
               ]),
               Input::make('client.email')->type('email')->title('email')->required(),
               DateTimer::make('client.birthday')->format('Y-m-d')->title('дата рождения')->required(),
               Relation::make('client.service_id')->fromModel(Service::class, 'name')->title('тип улуги')->required()->help('Тип оказанных услуг'),
               Select::make('client.assessment')->required()->options([
                'Отлично' => 'Отлично',
                'Хорошо' => 'Хорошо',
                'Удовлетварительно' => 'Удовлетварительно',
                'Плохо' => 'Плохо'
            ])->help('Реакция на оказанную услугу')->empty('Не известно', 'Не известно')
        ];
    }
}
