<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request): array|string|null
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln($request->all()['account']['subdomain']);
        return response();
    }
}
