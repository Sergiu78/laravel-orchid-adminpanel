<?php

namespace App\Orchid\Screens\Client;

use App\Models\Client;
use App\Models\Service;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use App\Http\Requests\ClientRequest;
use Orchid\Screen\Actions\ModalToggle;
use App\Orchid\Layouts\Client\ClientListTable;

class ClientListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Клиенты';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'clients' => Client::filters()->defaultSort('status', 'desc')->paginate(10)
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Создать клиента')->modal('createClient')->method('create')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
           ClientListTable::class,
           Layout::modal('createClient', Layout::rows([
               Input::make('phone')->required()->title('телефон')->mask('(999)999-9999'),
               Group::make([
                Input::make('name')->required()->title('имя'),
                Input::make('last_name')->title('фамилия')->required(),
               ]),
               Input::make('email')->type('email')->title('email')->required(),
               DateTimer::make('birthday')->format('Y-m-d')->title('дата рождения')->required(),
               Relation::make('service_id')->fromModel(Service::class, 'name')->title('тип улуги')
           ]))->title('Создать клиента')->applyButton('Создать')
        ];
    }

    public function create(ClientRequest $request): void
    {
       

        Client::create(array_merge($request->validated(), [
            'status' => 'interviewed'
        ]));
        Toast::info('Клиент успешно создан');
    }

    
}
