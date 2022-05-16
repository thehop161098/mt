<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqsController extends Controller
{
    private $model;
    public function __construct(Faq $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $faqs = $this->model->orderBy('sort', 'asc')->get();
        return view('frontend.faqs.index', compact('faqs'));
    }

}
