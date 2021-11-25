<?php

namespace App\Orchid\Layouts\Client;

use App\Models\Client;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ClientListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'clients';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('phone', 'Телефон')->width('150px')->cantHide()->canSee($this->isWorkTime())->filter(TD::FILTER_TEXT),
            TD::make('status', 'Статус')->render(function (Client $client) {
                return $client->status === 'interviewed' ? "Опрошен" : 'Не опрошен';
            })->width('150px')->popover('Статус по результатам работы оператора')->sort(),
            TD::make('email', 'Email')->width('150px'),
            TD::make('assessment', 'Оценка')->width('100px')->align(TD::ALIGN_RIGHT),
            TD::make('created_at', 'Дата создания')->defaultHidden(),
            TD::make('updated_at', 'Дата обнавления')->defaultHidden(),
            TD::make('action')->render(function (Client $client) {
                return ModalToggle::make('Редактировать', $client->phone)
                    ->modal('editClient')
                    ->method('createOrUpdateClient')
                    ->modalTitle('Редактировать клиента')
                    ->asyncParameters([
                        'client' => $client->id,
                    ]);
            })->width('100px')->align(TD::ALIGN_RIGHT),
        ];
    }
    private function isWorkTime(): bool
    {
        $lunch = CarbonPeriod::create('13:00', '14:00');
        return $lunch->contains(Carbon::now(config('app.timezone'))) === false;
    }

}
