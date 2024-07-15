<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class LeadsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private AmoCRMApiClient $amoCRMApiClient;

    public function __construct(AmoCRMApiClient $amoCRMApiClient)
    {
        $this->amoCRMApiClient = $amoCRMApiClient;
    }

    public function change_stage(Request $request): array|string|null
    {
        $lead_id = $request->all()['leads']['status'][0]['id'];

        $notesCollection = new NotesCollection();
        $serviceMessageNote = new ServiceMessageNote();
        $serviceMessageNote->setEntityId($lead_id)
            ->setText('Текст примечания')
            ->setService('Тестовая интеграция');
        $notesCollection->add($serviceMessageNote);

        $this->amoCRMApiClient->notes(EntityTypesInterface::LEADS)->add($notesCollection);

        return response(['OK'], 200);
    }
}
