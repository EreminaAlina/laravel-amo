<?php


use App\Jobs\UpdateLeadsJob;

class FetchLeads
{
    public function __invoke()
    {

        UpdateLeadsJob::dispatch();
    }
}
