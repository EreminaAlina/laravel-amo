<?php

namespace App\Jobs;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\NoteType\CommonNote;
use App\Models\Leads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use Illuminate\Support\Facades\Log;

class UpdateLeadsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(AmoCRMApiClient $amoCRMApiClient): void
    {
        foreach (Leads::getAllLeads() as $lead) {
            try {
                $pipelinesCollection = $amoCRMApiClient->pipelines()->get();
            } catch (AmoCRMApiException $e) {
                Log::error($e);
                die;
            }

            $pipeline1 = null;
            foreach ($pipelinesCollection->toArray() as $pipeline) {
                if ($pipeline['id'] == $lead['pipeline_id']) {
                    $pipeline1 = $pipeline;
                }
            }

            $statusesCollection = $amoCRMApiClient->statuses($pipeline1['id'])->get();
            $leadData = json_decode(Leads::getLeadById($lead['lead_id'])->data);

            $oldStatusId = $leadData->old_status_id;
            $newStatusId = $leadData->status_id;
            $oldStatus = null;
            foreach ($statusesCollection->toArray() as $status) {
                if ($status['id'] == $oldStatusId) {
                    $oldStatus = $status['name'];
                }
            }

            $newStatus = null;
            foreach ($statusesCollection->toArray() as $status) {
                if ($status['id'] == $newStatusId) {
                    $newStatus = $status['name'];
                }
            }

            $message = 'Статус сделки изменён с ' . $oldStatus . ' на ' . $newStatus;

            $notesCollection = new NotesCollection();
            $commonNote = new CommonNote();
            $commonNote->setEntityId($lead['lead_id'])
                ->setText($message);
            $notesCollection->add($commonNote);
            $amoCRMApiClient->notes(EntityTypesInterface::LEADS)->add($notesCollection);

            Leads::deleteLeadById($lead['lead_id']);
        }
    }
}
