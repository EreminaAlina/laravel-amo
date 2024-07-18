<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use App\Models\Leads;
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
        $lead = $request->all()['leads']['status'][0];
        $this->handle($lead);

        return response(['OK'], 200);
    }

    private function handle(array $data)
    {
        if (isset($data['id'])) {
            $lead = Leads::getLeadById($data['id']);

            if($lead) {
                if ($lead->last_modified < (int) $data['last_modified']) {
                    Leads::updateLead($data['id'], $data['last_modified'], $data['pipeline_id'], $data);
                }
            }  else {
                Leads::createLead($data['id'], $data['last_modified'], $data['pipeline_id'], $data);
            }
        }
    }
}
