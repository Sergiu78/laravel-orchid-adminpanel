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
use App\Orchid\Layouts\CreateOrUpdateClient;
use Orchid\Screen\Fields\Select;

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
            ModalToggle::make('Создать клиента')->modal('createClient')->method('createOrUpdateClient'),
            
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
           Layout::modal('createClient', CreateOrUpdateClient::class)->title('Создать клиента')->applyButton('Создать'),
           Layout::modal('editClient', CreateOrUpdateClient::class)->applyButton('Применить')->async('asyncClient')
        
        ];
    }

    public function asyncClient(Client $client): array
    {
        return [
            'client' => $client
        ];
    }

    

    public function createOrUpdateClient(ClientRequest $request): void
    {
        $clientId = $request->input('client.id');
        Client::updateOrCreate([
            'id' => $clientId
        ], array_merge($request->validated()['client'], [
            'status' => 'interviewed'
        ]));

        is_null($clientId) ? Toast::info('Клиент создан') : Toast::info('Клиент обнавлён');
    }

    
}
